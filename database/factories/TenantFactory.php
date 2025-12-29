<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'domain' => $this->faker->domainName,
            'email' => $this->faker->unique()->companyEmail,
            'is_active' => true,
        ];
    }
}
