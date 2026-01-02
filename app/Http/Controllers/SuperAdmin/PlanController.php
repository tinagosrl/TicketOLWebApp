<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $request->validate([
            'name' => 'required|array',
            'description' => 'nullable|array',
            'features_html' => 'nullable|array',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'ticket_limit' => 'required|min:0',
            'max_subadmins' => 'required|integer|min:0',
            'max_venues' => 'required|integer|min:0', // <--- Added validation
            'application_fee_percent' => 'required|numeric|min:0|max:100',
            'position' => 'nullable|integer',
            'is_recommended' => 'boolean',
            
            'is_active' => 'boolean',
            'allowed_event_types' => 'array',
        ]);

        
        $data = $request->all();
        $data['is_recommended'] = $request->has('is_recommended');
        $data['is_active'] = $request->has('is_active');
        $data['allowed_event_types'] = $request->input('allowed_event_types', []);
        
        $plan->update($data);
    

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }
}
