<?php

namespace Database\Factories;

use App\Models\Hackathon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HackathonTeam>
 */
class HackathonTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hackathon_id' => Hackathon::factory(),
            'name' => fake()->company().' Team',
            'leader_id' => User::factory(),
            'description' => fake()->optional()->paragraph(),
            'project_name' => fake()->optional()->words(3, true),
            'project_description' => fake()->optional()->paragraph(),
            'project_repository' => fake()->optional()->url(),
            'is_public' => true,
        ];
    }
}
