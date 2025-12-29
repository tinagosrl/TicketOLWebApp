<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(): View
    {
        $events = auth()->user()->tenant->events()->with('venue')->get();
        return view('tenant.events.index', compact('events'));
    }

    public function create(): View
    {
        $venues = auth()->user()->tenant->venues;
        return view('tenant.events.create', compact('venues'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Ensure venue belongs to tenant
        $venue = Venue::findOrFail($request->venue_id);
        if ($venue->tenant_id !== auth()->user()->tenant_id) abort(403);

        $data = $request->except('image');
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('tenant.events.edit', $event)->with('success', 'Event created. Add ticket types now.');
    }

    public function edit(Event $event): View
    {
        if ($event->tenant_id !== auth()->user()->tenant_id) abort(403);
        $venues = auth()->user()->tenant->venues;
        $ticketTypes = $event->ticketTypes;
        
        return view('tenant.events.edit', compact('event', 'venues', 'ticketTypes'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($event->tenant_id !== auth()->user()->tenant_id) abort(403);

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return back()->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->tenant_id !== auth()->user()->tenant_id) abort(403);
        $event->delete();
        return redirect()->route('tenant.events.index')->with('success', 'Event deleted.');
    }
}
