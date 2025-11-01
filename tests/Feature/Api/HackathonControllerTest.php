<?php

namespace Tests\Feature\Api;

use App\Models\Hackathon;

class HackathonControllerTest extends ApiTestCase
{
    public function test_guest_can_view_hackathons(): void
    {
        Hackathon::factory()->count(5)->create();

        $response = $this->getJson($this->apiUrl('hackathons'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ]);
    }

    public function test_guest_can_view_single_hackathon(): void
    {
        $hackathon = Hackathon::factory()->create();

        $response = $this->getJson($this->apiUrl("hackathons/{$hackathon->id}"));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $hackathon->id);
    }

    public function test_authenticated_user_can_create_hackathon(): void
    {
        $user = $this->createClientUser();

        $response = $this->postJson($this->apiUrl('hackathons'), [
            'title' => 'AI Hackathon 2024',
            'description' => 'An AI-focused hackathon',
            'start_date' => now()->addMonth()->toISOString(),
            'end_date' => now()->addMonth()->addDays(2)->toISOString(),
            'registration_deadline' => now()->addWeeks(3)->toISOString(),
            'max_team_size' => 4,
            'min_team_size' => 2,
            'format' => 'online',
            'location' => null,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('hackathons', [
            'title' => 'AI Hackathon 2024',
            'created_by' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_create_team(): void
    {
        $user = $this->createAuthenticatedUser();
        $hackathon = Hackathon::factory()->create([
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
        ]);

        $response = $this->postJson($this->apiUrl('hackathons/teams'), [
            'hackathon_id' => $hackathon->id,
            'name' => 'Awesome Team',
            'description' => 'We are awesome',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('hackathon_teams', [
            'hackathon_id' => $hackathon->id,
            'name' => 'Awesome Team',
            'leader_id' => $user->id,
        ]);
    }
}
