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
            'position' => 'nullable|integer',
            'is_recommended' => 'boolean',
            
            'is_active' => 'boolean',
        ]);

        
        $data = $request->all();
        $data['is_recommended'] = $request->has('is_recommended');
        $data['is_active'] = $request->has('is_active');
        
        $plan->update($data);
    

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }
}
