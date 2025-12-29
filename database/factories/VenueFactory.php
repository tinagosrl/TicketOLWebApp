<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->city,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'capacity' => 1000,
        ];
    }
}
