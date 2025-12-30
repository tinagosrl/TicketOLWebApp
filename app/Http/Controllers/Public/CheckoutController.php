<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Store a newly created resource in storage.
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
            DB::beginTransaction();

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
                
                // Verify availability again
                $ticketType = TicketType::lockForUpdate()->find($item['id']);
                if ($ticketType->quantity < $item['quantity']) {
                    throw new \Exception("Not enough tickets available for {$ticketType->name}");
                }
                $ticketType->decrement('quantity', $item['quantity']);
                $ticketType->increment('sold', $item['quantity']);
            }

            $order = Order::create([
                'tenant_id' => $tenant->id,
                'reference_no' => 'ORD-' . strtoupper(Str::random(10)), // Simple ref for now
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_amount' => $total,
                'status' => 'paid', // Assuming instant payment or free for MVP
            ]);

            foreach ($cart as $item) {
                $orderItem = $order->items()->create([
                    'ticket_type_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Generate individual tickets
                for ($i = 0; $i < $item['quantity']; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'ticket_type_id' => $item['id'],
                        'unique_code' => Str::random(12),
                    ]);
                }
            }

            DB::commit();
            Session::forget('cart');

            // Send Email
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            return redirect()->route('public.shop.checkout.success', $order->reference_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the success page.
     */
    public function success(Request $request, $reference)
    {
        $tenant = $request->get('tenant');
        $order = Order::where('reference_no', $reference)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        return view('public.checkout.success', compact('order', 'tenant'));
    }
}
