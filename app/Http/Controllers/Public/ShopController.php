<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenant = $request->get('tenant'); // Injected by middleware or resolver
        
        $events = Event::with('venue')
            ->where('tenant_id', $tenant->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate(9);

        return view('public.shop.index', compact('events', 'tenant'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $slug)
    {
        $tenant = $request->get('tenant');
        
        $event = Event::with(['venue', 'ticketTypes' => function($q) {
                // Ensure only available ticket types or all
                // $q->where('quantity', '>', 0); // Optional: Hide sold out
            }])
            ->where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.shop.show', compact('event', 'tenant'));
    }
}
