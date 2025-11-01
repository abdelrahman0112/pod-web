<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 years', '-1 year');
        $endDate = fake()->optional(0.7)->dateTimeBetween($startDate, 'now');

        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'company' => fake()->company(),
            'company_url' => fake()->optional()->url(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_current' => $endDate === null,
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
