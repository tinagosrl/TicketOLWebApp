<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Ideal for small events.',
                'price_monthly' => 29.00,
                'price_yearly' => 290.00,
                'ticket_limit' => 500,
                'max_subadmins' => 0,
            ],
            [
                'name' => 'Professional',
                'slug' => 'pro',
                'description' => 'For growing museums and venues.',
                'price_monthly' => 99.00,
                'price_yearly' => 990.00,
                'ticket_limit' => 5000,
                'max_subadmins' => 1,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Advanced features and dedicated support.',
                'price_monthly' => 299.00,
                'price_yearly' => 2990.00,
                'ticket_limit' => 0, // Unlimited
                'max_subadmins' => 5,
            ],
            [
                'name' => 'Open Access',
                'slug' => 'open-access',
                'description' => 'Custom solutions for large institutions.',
                'price_monthly' => 499.00,
                'price_yearly' => 4990.00,
                'ticket_limit' => 0, // Unlimited
                'max_subadmins' => 5,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
