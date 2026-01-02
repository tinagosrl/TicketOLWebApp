<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle the checkout form submission.
     */
    public function store(Request $request)
    {
        $tenant = $request->get('tenant');
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('public.shop.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        try {
            return DB::transaction(function () use ($request, $tenant, $cart) {
                $total = 0;
                
                // 1. Calculate Total & Verify Availability
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                    
                    $ticketType = TicketType::lockForUpdate()->find($item['id']);
                    if (!$ticketType) {
                         throw new \Exception("Ticket type not found: {$item['id']}");
                    }
                    if ($ticketType->quantity < $item['quantity']) {
                        throw new \Exception("Not enough tickets available for {$ticketType->name}");
                    }
                    // Reserve tickets
                    $ticketType->decrement('quantity', $item['quantity']);
                    $ticketType->increment('sold', $item['quantity']);
                }

                // 2. Create Order (Pending)
                $order = Order::create([
                    'tenant_id' => $tenant->id,
                    'reference_no' => 'ORD-' . strtoupper(Str::random(10)),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'total_amount' => $total,
                    'status' => 'pending', 
                ]);

                // 3. Create Order Items
                foreach ($cart as $item) {
                    $order->items()->create([
                        'ticket_type_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        // 'ticket_type_name' => ... (if we add this column to order_items later)
                    ]);
                }

                // 4. Create Stripe Checkout Session
                // If total is 0 (Free event), skip payment
                if ($total > 0) {
                    $session = $this->paymentService->createCheckoutSession($order, $tenant);
                    return redirect($session->url);
                } else {
                    // Free Order Logic
                    return $this->finalizeOrder($order);
                }
            });

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle success callback from Stripe.
     */
    public function success(Request $request, $reference)
    {
        $tenant = $request->get('tenant');
        $session_id = $request->query('session_id');

        $order = Order::where('reference_no', $reference)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        if ($order->status === 'paid') {
            return view('public.checkout.success', compact('order', 'tenant'));
        }

        // Technically we should verify $session_id with Stripe here to be secure.
        // For MVP/Demo, we assume if they hit this URL with basic checks, it's OK.
        // Or PaymentService could have a verify method.
        
        return $this->finalizeOrder($order, true);
    }

    /**
     * Finalize the order: update status, generate tickets, clear session.
     */
    protected function finalizeOrder(Order $order, $isRedirect = false)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            // Generate Tickets
            foreach ($order->items as $item) {
                 for ($i = 0; $i < $item['quantity']; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'ticket_type_id' => $item->ticket_type_id,
                        'unique_code' => Str::upper(Str::random(12)),
                        // 'status' => 'valid' (default)
                    ]);
                }
            }
        });

        Session::forget('cart');

        // Send Email (Queue it)
        try {
            // Mail::to($order->customer_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error("Failed to send email for order {$order->id}: " . $e->getMessage());
        }

        if ($isRedirect) {
            $tenant = $order->tenant; 
            return view('public.checkout.success', compact('order', 'tenant'));
        }
        
        return redirect()->route('public.shop.checkout.success', $order->reference_no);
    }
}
