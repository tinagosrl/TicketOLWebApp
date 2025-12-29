<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)->orderBy('price_monthly')->get();
        return view('auth.register', compact('plans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:60', 'unique:'.Tenant::class, 'alpha_dash'],
            'plan_id' => ['required', 'exists:plans,id'],
            'discount_code' => ['nullable', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'unique:'.Tenant::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->tenant_name,
                'email' => $request->email,
                'domain' => Str::slug($request->domain),
                'is_active' => false, 
            ]);

            // 2. Create User linked to Tenant
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role' => 'tenant_admin',
            ]);

            // 3. Create Pending Subscription
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $request->plan_id,
                'status' => 'pending',
                'billing_cycle' => 'monthly',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(), 
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('billing.payment');
        });
    }
}
