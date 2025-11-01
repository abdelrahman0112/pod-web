<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d').' +1 day');
        $registrationDeadline = fake()->dateTimeBetween('now', $startDate->format('Y-m-d').' -1 day');

        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->address(),
            'format' => fake()->randomElement(['online', 'in-person', 'hybrid']),
            'max_attendees' => fake()->optional(0.7)->randomElement([50, 100, 200, 500]),
            'registration_deadline' => $registrationDeadline,
            'waitlist_enabled' => fake()->boolean(30),
            'category_id' => \App\Models\EventCategory::factory(),
            'created_by' => User::factory(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
