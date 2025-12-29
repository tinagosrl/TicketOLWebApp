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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'ticket_limit' => 'required|min:0',
            'max_subadmins' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $plan->update($request->all());

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }
}
