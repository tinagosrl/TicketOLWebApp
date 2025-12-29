<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tenant;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request, $domain)
    {
        $tenant = Tenant::where('domain', $domain)->firstOrFail();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'tickets' => 'required|array',
            'tickets.*' => 'integer|min:0',
        ]);

        // Calculate total and validate availability
        $totalAmount = 0;
        $itemsToCreate = [];

        foreach ($validated['tickets'] as $ticketId => $quantity) {
            if ($quantity > 0) {
                // Determine if this ticket belongs to an event of this tenant
                // Ideally we check relation, for speed we just check existence
                $ticketType = TicketType::findOrFail($ticketId);
                
                // TODO: Verify ticket belongs to tenant's event
                
                $price = $ticketType->price;
                $totalAmount += $price * $quantity;

                $itemsToCreate[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }
        }

        if (empty($itemsToCreate)) {
            return back()->withErrors(['tickets' => 'Please select at least one ticket.']);
        }

        // Create Order
        $order = Order::create([
            'tenant_id' => $tenant->id,
            'reference_no' => strtoupper(Str::random(10)), // Simple ref
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'total_amount' => $totalAmount,
            'status' => 'paid', // Simulating successful payment
        ]);

        // Create Items
        foreach ($itemsToCreate as $item) {
            $order->items()->create($item);
            
            // Update sold count
            $ticketType = TicketType::find($item['ticket_type_id']);
            $ticketType->increment('sold', $item['quantity']);
        }

        return redirect()->route('public.shop.checkout.success', ['domain' => $domain, 'reference' => $order->reference_no]);
    }

    public function success($domain, $reference)
    {
         $tenant = Tenant::where('domain', $domain)->firstOrFail();
         $order = Order::where('tenant_id', $tenant->id)
             ->where('reference_no', $reference)
             ->with(['items.ticketType.event'])
             ->firstOrFail();

         return view('public.shop.success', compact('tenant', 'order'));
    }
}
