<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Plan;

class PaymentController extends Controller
{
    public function show(Request $request): View
    {
        // Get user's plan via subscription or session?
        // Assuming user is logged in from registration.
        $user = $request->user();
        
        // Just mocking data for now as per "Fake Payment" request
        // In real app we check $user->tenant->subscription->plan
        // But for now let's just show a default or fetch if relationship exists
        
        $plan_name = "Premium Plan";
        $amount = "29.00";
        
        // Try getting real data
        if ($user && $user->tenant && $user->tenant->subscription && $user->tenant->subscription->plan) {
             $plan = $user->tenant->subscription->plan;
             $plan_name = $plan->getTranslation('name'); // Multilingual support
             $amount = number_format($plan->price_monthly, 2);
        }

        return view('auth.payment', compact('plan_name', 'amount'));
    }

    public function process(Request $request): RedirectResponse
    {
        // Mock Processing
        // sleep(1);
        
        // TODO: Mark subscription as active or paid if needed.
        
        return redirect()->route('dashboard')->with('success', 'Payment successful! Welcome aboard.');
    }
}