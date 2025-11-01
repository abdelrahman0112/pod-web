<?php

namespace Database\Factories;

use App\JobApplicationStatus;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_listing_id' => JobListing::factory(),
            'user_id' => User::factory(),
            'cover_letter' => fake()->paragraphs(3, true),
            'additional_info' => fake()->optional()->paragraph(),
            'status' => JobApplicationStatus::PENDING,
        ];
    }
}
