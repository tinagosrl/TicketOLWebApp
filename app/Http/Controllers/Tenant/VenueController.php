<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function index(): View
    {
        $venues = auth()->user()->tenant->venues;
        return view('tenant.venues.index', compact('venues'));
    }

    public function create(): View
    {
        return view('tenant.venues.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'opening_hours' => 'nullable|array',
        ]);

        auth()->user()->tenant->venues()->create($request->all());

        return redirect()->route('tenant.venues.index')->with('success', 'Venue created successfully.');
    }

    public function edit(Venue $venue): View
    {
        if ($venue->tenant_id !== auth()->user()->tenant_id) abort(403);
        return view('tenant.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue): RedirectResponse
    {
        if ($venue->tenant_id !== auth()->user()->tenant_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'opening_hours' => 'nullable|array',
        ]);

        $venue->update($request->all());

        return redirect()->route('tenant.venues.index')->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        if ($venue->tenant_id !== auth()->user()->tenant_id) abort(403);
        $venue->delete();
        return back()->with('success', 'Venue deleted successfully.');
    }
}
