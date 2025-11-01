<?php

namespace Database\Factories;

use App\ExperienceLevel;
use App\LocationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
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
            'description' => fake()->paragraphs(5, true),
            'company_name' => fake()->company(),
            'company_description' => fake()->optional()->paragraph(),
            'location_type' => fake()->randomElement(array_column(LocationType::cases(), 'value')),
            'location' => fake()->city(),
            'salary_min' => fake()->optional(0.8)->numberBetween(40000, 100000),
            'salary_max' => fake()->optional(0.8)->numberBetween(100000, 200000),
            'required_skills' => fake()->randomElements(['PHP', 'Laravel', 'JavaScript', 'React', 'Vue', 'Python', 'Node.js'], 3),
            'experience_level' => fake()->randomElement(array_column(ExperienceLevel::cases(), 'value')),
            'application_deadline' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'category_id' => \App\Models\Category::factory(),
            'posted_by' => User::factory(),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the job listing is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the job listing is accepting applications.
     */
    public function acceptingApplications(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'application_deadline' => now()->addWeek(),
        ]);
    }
}
