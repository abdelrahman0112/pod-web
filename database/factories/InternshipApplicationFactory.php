<?php

namespace Database\Factories;

use App\InternshipApplicationStatus;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternshipApplication>
 */
class InternshipApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'internship_id' => Internship::factory(),
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? fake()->phoneNumber(),
            'university' => fake()->company(),
            'major' => fake()->word(),
            'graduation_status' => fake()->randomElement(['graduated', 'student', 'alumni']),
            'experience' => fake()->paragraph(),
            'interest_categories' => fake()->randomElements(['AI', 'ML', 'Data Science'], 2),
            'availability_start' => fake()->date(),
            'availability_end' => fake()->date(),
            'motivation' => fake()->paragraph(),
            'status' => InternshipApplicationStatus::PENDING,
        ];
    }
}
