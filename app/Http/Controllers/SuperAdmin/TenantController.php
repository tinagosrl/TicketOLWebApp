<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('currentPlan.plan')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('admin.tenants.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'email' => 'required|email|max:255|unique:tenants,email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $tenant = Tenant::create([
            'name' => $request->name,
            'domain' => $request->domain,
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Create Tenant Admin User
        User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name . ' Admin',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tenant_admin',
            'email_verified_at' => now(),
        ]);

        // Assign Plan (Create Subscription)
        $tenant->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'status' => 'active',
            'starts_at' => now(),
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant and Admin User created successfully.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $plans = Plan::all(); // Show all plans even if inactive, in case they are on one
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => ['required', 'string', 'max:255', Rule::unique('tenants')->ignore($tenant->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('tenants')->ignore($tenant->id)],
            'is_active' => 'boolean',
            // Plan update logic handled separately ideally, but for MVP can be here
            // 'plan_id' => 'exists:plans,id'
        ]);

        $tenant->update([
            'name' => $request->name,
            'domain' => $request->domain,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // Delete users? Or soft delete tenant?
        // Basic implementation for now
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('currentPlan.plan', 'users');
        return view('admin.tenants.show', compact('tenant'));
    }
    
    public function impersonate(Tenant $tenant)
    {
        $user = $tenant->users()->first(); // Assuming first user is Admin
        if (!$user) {
            return back()->with('error', 'Nessun utente trovato per questo tenant.');
        }

        session(['impersonator_id' => auth()->id()]);
        
        \Illuminate\Support\Facades\Auth::login($user);

        \App\Models\ImpersonationLog::create([
            'impersonator_id' => session('impersonator_id'),
            'impersonated_id' => $user->id,
            'action' => 'enter',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    

        return redirect()->route('dashboard');
    }

}
