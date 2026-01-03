<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;
use App\Models\Tenant;

class PaymentService
{
    // Constructor removed to prevent early failure if config is missing
    /*
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    */

    public function createCheckoutSession(Order $order, Tenant $tenant)
    {
        // Lazy load API Key
        $apiKey = config('services.stripe.secret');
        if (!$apiKey) {
             throw new \Exception('Stripe Secret Key not configured in system settings.');
        }
        Stripe::setApiKey($apiKey);

        if (!$tenant->stripe_account_id) {
            throw new \Exception('Il venditore non ha ancora configurato i pagamenti (Stripe Connect).');
        }

        // Calculate Application Fee
        $applicationFeeParam = [];
        $plan = $tenant->subscription->plan ?? null;
        
        if ($plan && $plan->application_fee_percent > 0) {
            // Fee is a percentage of the total amount
            // Amount in cents
            $amountCents = (int) ($order->total_amount * 100);
            $feeAmount = (int) round($amountCents * ($plan->application_fee_percent / 100));
            
            if ($feeAmount > 0) {
                // Stripe requires a positive integer
                $applicationFeeParam = [
                    'application_fee_amount' => $feeAmount,
                ];
            }
        }

        // Prepare Line Items
        $lineItems = [];
        foreach ($order->items as $item) {
            // Check if relationship exists, otherwise use fallback name
            $itemName = $item->ticketType ? $item->ticketType->name : 'Biglietto';
            
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) ($item->price * 100), // OrderItem has 'price' column in new controller logic
                    'product_data' => [
                        'name' => $itemName, 
                    ],
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Create Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('public.shop.checkout.success', ['domain' => $tenant->domain, 'reference' => $order->reference_no, 'session_id' => '{CHECKOUT_SESSION_ID}']),
            'cancel_url' => route('public.cart.index', ['domain' => $tenant->domain]),
            'payment_intent_data' => array_merge([
                // Metadata for tracking
                'metadata' => [
                    'order_id' => $order->id,
                    'tenant_id' => $tenant->id,
                    'reference_no' => $order->reference_no,
                ],
            ], $applicationFeeParam), // Add application fee if exists
        ], [
            'stripe_account' => $tenant->stripe_account_id, // DIRECT CHARGE to Tenant
        ]);

        return $session;
    }
}
