<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart contents.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
            $items[] = $details;
        }

        $tenant = app('tenant');
        return view('public.cart.index', compact('items', 'total', 'tenant'));
    }

    /**
     * Add an item to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $ticketType = TicketType::with('event')->findOrFail($request->ticket_type_id);
        
        // Custom Validation for Min Purchase
        if ($request->quantity < $ticketType->min_purchase) {
            return redirect()->back()->with('error', "Minimum purchase quantity for {$ticketType->name} is {$ticketType->min_purchase}.");
        }

        $cart = Session::get('cart', []);

        // Unique key for cart item (just ticket_type_id for now as no variants)
        $id = $ticketType->id;

        if (isset($cart[$id])) {
            $currentQty = $cart[$id]['quantity'];
            $newQty = $currentQty + $request->quantity;
            $cart[$id]['quantity'] = $newQty;
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $ticketType->name,
                'event_name' => $ticketType->event->name,
                'price' => $ticketType->price,
                'quantity' => $request->quantity,
                'min_purchase' => $ticketType->min_purchase, // Store for potential re-checks
                // 'ticket_type_obj' => $ticketType  // Avoid storing full object in session if not needed
            ];
        }

        Session::put('cart', $cart);

        return redirect()->route('public.cart.index', ['domain' => $request->route('domain')])->with('success', 'Ticket added to cart!');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    /**
     * Show the checkout form.
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('public.shop.index', ['domain' => request()->route('domain')]);
        }

        $total = 0;
        $items = [];
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
            $items[] = $details;
        }

        return view('public.checkout.index', [
            'tenant' => app('tenant'),
            'cart' => $cart,
            'items' => $items,
            'total' => $total
        ]);
    }
}
