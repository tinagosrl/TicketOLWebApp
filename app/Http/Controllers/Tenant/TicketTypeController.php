<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TicketTypeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($request->event_id);
        
        // Security check: ensure event belongs to tenant
        if ($event->tenant_id !== auth()->user()->tenant_id) abort(403);

        $event->ticketTypes()->create($request->all());

        return back()->with('success', 'Ticket type added.');
    }

    public function destroy(TicketType $ticketType): RedirectResponse
    {
        $event = $ticketType->event;
        
        // Security check
        if ($event->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        if ($ticketType->sold > 0) {
            return back()->with('error', 'Cannot delete ticket type with sold tickets.');
        }

        $ticketType->delete();

        return back()->with('success', 'Ticket type deleted.');
    }
}
