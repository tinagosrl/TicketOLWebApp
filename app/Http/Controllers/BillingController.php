<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function payment()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Get pending subscription
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$subscription) {
            // Already active? Redirect to dashboard
            if ($user->tenant->is_active) {
                return redirect()->route('dashboard');
            }
            // Error state or create new subscription logic needed if missing
            return redirect()->route('dashboard')->with('error', 'No pending subscription found.');
        }

        $plan = $subscription->plan;
        
        return view('billing.payment', compact('plan', 'subscription'));
    }

    public function process(Request $request)
    {
        // FAKE PAYMENT LOGIC
        $user = Auth::user();
        $tenant = $user->tenant;

         $subscription = Subscription::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
            
        if ($subscription) {
            // Activate Subscription
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);

            // Activate Tenant
            $tenant->update(['is_active' => true]);

            // Generate Invoice
            Invoice::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->plan->price_monthly,
                'status' => 'paid',
                'issued_at' => now(),
                'paid_at' => now(),
                'pdf_path' => 'invoices/fake_invoice_' . now()->timestamp . '.pdf',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Payment successful! Welcome to TicketOL.');
    }
}
