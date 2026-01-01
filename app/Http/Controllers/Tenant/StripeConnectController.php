<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StripeConnectController extends Controller
{
    public function connect(Request $request)
    {
        $tenant = auth()->user()->tenant;
        
        if (! $tenant) {
             abort(403, 'Unauthorized action.');
        }

        $state = Str::random(40);
        session(['stripe_connect_state' => $state]);

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.stripe.client_id'),
            'scope' => 'read_write',
            'state' => $state,
            'redirect_uri' => route('tenant.stripe.callback'),
            'stripe_user[email]' => auth()->user()->email,
            'stripe_user[url]' => 'https://' . $tenant->domain,
        ]);

        return redirect('https://connect.stripe.com/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        if ($request->state !== session('stripe_connect_state')) {
            return redirect()->route('dashboard')->with('error', 'Invalid state parameter.');
        }

        if ($request->has('error')) {
            return redirect()->route('dashboard')->with('error', 'Stripe connection denied: ' . $request->error_description);
        }

        $code = $request->code;

        $response = Http::asForm()->post('https://connect.stripe.com/oauth/token', [
            'client_secret' => config('services.stripe.secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->failed()) {
            return redirect()->route('dashboard')->with('error', 'Failed to connect to Stripe: ' . $response->body());
        }

        $stripeUserId = $response->json('stripe_user_id');

        $tenant = auth()->user()->tenant;
        $tenant->update(['stripe_account_id' => $stripeUserId]);

        return redirect()->route('dashboard')->with('success', 'Stripe connection established successfully! You can now accept payments.');
    }
}
