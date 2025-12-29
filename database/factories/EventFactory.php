<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        return [
            'tenant_id' => Tenant::factory(),
            'venue_id' => Venue::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(6),
        ];
    }
}
