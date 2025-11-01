<?php

namespace Database\Factories;

use App\Models\InternshipCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Internship>
 */
class InternshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'company_name' => fake()->company(),
            'category_id' => InternshipCategory::factory(),
            'location' => fake()->city(),
            'type' => fake()->randomElement(['full_time', 'part_time', 'remote', 'hybrid']),
            'duration' => fake()->randomElement(['3 months', '6 months', '1 year']),
            'application_deadline' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'start_date' => fake()->dateTimeBetween('+1 month', '+4 months'),
            'status' => 'open',
        ];
    }
}
