<?php

namespace Database\Factories;

use App\Models\HackathonTeam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HackathonTeamInvitation>
 */
class HackathonTeamInvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => HackathonTeam::factory(),
            'inviter_id' => User::factory(),
            'invitee_id' => User::factory(),
            'status' => 'pending',
            'message' => fake()->optional()->sentence(),
        ];
    }
}
