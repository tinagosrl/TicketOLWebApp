<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => 'General Admission',
            'price' => 50.00,
            'quantity' => 100,
            'sold' => 0,
        ];
    }
}
