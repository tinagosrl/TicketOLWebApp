<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'reference_no' => 'ORD-' . Str::random(10),
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'total_amount' => 100,
            'status' => 'paid',
        ];
    }
}
