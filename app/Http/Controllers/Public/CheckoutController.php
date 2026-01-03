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
            return redirect()->route('public.shop.index', ['domain' => request()->route('domain')])->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'consolidate_tickets' => 'nullable|boolean', // 1 = Single QR per type
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
                    
                    // Check Quantity (if not unlimited)
                    if ($ticketType->quantity != -1) {
                        if ($ticketType->quantity < $item['quantity']) {
                            throw new \Exception("Not enough tickets available for {$ticketType->name}");
                        }
                        // Reserve tickets (decrement)
                        $ticketType->decrement('quantity', $item['quantity']);
                    }
                    
                    // Always increment sold count
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

                if ($request->has('consolidate_tickets')) {
                    Session::put('consolidate_tickets_' . $order->id, true);
                }

                // 3. Create Order Items
                foreach ($cart as $item) {
                    $order->items()->create([
                        'ticket_type_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
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

        } catch (\Throwable $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            // CRITICAL: withInput() prevents form reset
            return redirect()->back()->withInput()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle success callback from Stripe.
     */
    public function success(Request $request, $reference)
    {
        $tenant = $request->get('tenant');
        
        $order = Order::where('reference_no', $reference)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        if ($order->status === 'paid') {
            return view('public.checkout.success', compact('order', 'tenant'));
        }
        
        return $this->finalizeOrder($order, true);
    }

    /**
     * Finalize the order: update status, generate tickets, clear session.
     */
    protected function finalizeOrder(Order $order, $isRedirect = false)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            // Determine if consolidation was requested
            $consolidate = Session::get('consolidate_tickets_' . $order->id, false);
            Session::forget('consolidate_tickets_' . $order->id); // Cleanup

            // Generate Tickets
            foreach ($order->items as $item) {
                if ($consolidate && $item->quantity > 1) {
                    // SINGLE Ticket with Multiple Uses
                     Ticket::create([
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'ticket_type_id' => $item->ticket_type_id,
                        'unique_code' => Str::upper(Str::random(12)),
                        'status' => 'valid',
                        'remaining_uses' => $item->quantity, // N uses
                    ]);
                } else {
                    // SEPARATE Tickets (1 use each)
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        Ticket::create([
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                            'ticket_type_id' => $item->ticket_type_id,
                            'unique_code' => Str::upper(Str::random(12)),
                            'status' => 'valid',
                            'remaining_uses' => 1,
                        ]);
                    }
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
        
        return redirect()->route('public.shop.checkout.success', ['domain' => $order->tenant->domain, 'reference' => $order->reference_no]);
    }
}
