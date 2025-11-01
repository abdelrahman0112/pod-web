<?php

namespace Database\Factories;

use App\EventRegistrationStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'status' => EventRegistrationStatus::CONFIRMED,
            'ticket_code' => 'EVT-'.fake()->unique()->numerify('####-########'),
            'text_code' => fake()->unique()->regexify('[A-Z0-9]{6}'),
            'checked_in' => false,
            'checked_in_at' => null,
        ];
    }
}
