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
        $rules = [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'vertical_image' => 'nullable|image|max:2048',
        ];

        // Conditional Validation
        if ($request->type === 'open') {
            $rules['end_date'] = 'nullable|date|after_or_equal:start_date';
        } else {
            // Scheduled: Require end date
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $request->validate($rules);
        
        // Ensure venue belongs to tenant
        $venue = Venue::findOrFail($request->venue_id);
        if ($venue->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        // Check Allowed Types (Plan Enforcement) - Added for consistency
        $allowedTypes = auth()->user()->tenant->currentPlan->plan->allowed_event_types ?? ['scheduled', 'open'];
        // Default to scheduled if type not sent? View sends type.
        $type = $request->type ?? 'scheduled';
        
        if (!in_array($type, $allowedTypes)) {
             // Forcing fail or defaulting?
             // Since UI hides options, this is backend security.
             // If validation fails, error.
             return back()->with('error', 'Event type not allowed by plan.');
        }

        $data = $request->except(['image', 'vertical_image']);
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        $data['type'] = $type; // Ensure type is saved
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        if ($request->hasFile('vertical_image')) {
            $data['vertical_image_path'] = $request->file('vertical_image')->store('events', 'public');
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

        $rules = [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'vertical_image' => 'nullable|image|max:2048',
        ];

        if ($request->type === 'open') {
             $rules['end_date'] = 'nullable|date|after_or_equal:start_date';
        } else {
             $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $request->validate($rules);

        $allowedTypes = auth()->user()->tenant->currentPlan->plan->allowed_event_types ?? ['scheduled', 'open'];
        if ($request->has('type') && !in_array($request->type, $allowedTypes)) {
            return back()->with('error', 'The selected event type is not allowed with your current plan.');
        }

        $data = $request->except(['image', 'vertical_image']);
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        if ($request->hasFile('vertical_image')) {
            $data['vertical_image_path'] = $request->file('vertical_image')->store('events', 'public');
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
