<?php

namespace Tests\Feature\Api;

use App\Models\Hackathon;
use App\Models\HackathonTeam;
use App\Models\HackathonTeamInvitation;
use App\Models\User;

class HackathonControllerAdditionalTest extends ApiTestCase
{
    public function test_authenticated_user_can_register_for_hackathon(): void
    {
        $user = $this->createAuthenticatedUser();
        $hackathon = Hackathon::factory()->create([
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
        ]);

        $response = $this->postJson($this->apiUrl("hackathons/{$hackathon->id}/register"));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ]);
    }

    public function test_authenticated_user_can_view_hackathon_teams(): void
    {
        $user = $this->createAuthenticatedUser();
        $hackathon = Hackathon::factory()->create();
        HackathonTeam::factory()->count(3)->create(['hackathon_id' => $hackathon->id]);

        $response = $this->getJson($this->apiUrl("hackathons/{$hackathon->id}/teams"));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
        $response->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_update_team(): void
    {
        $user = $this->createAuthenticatedUser();
        $hackathon = Hackathon::factory()->create([
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
        ]);
        $team = HackathonTeam::factory()->create([
            'hackathon_id' => $hackathon->id,
            'leader_id' => $user->id,
        ]);

        $response = $this->putJson($this->apiUrl("hackathons/teams/{$team->id}"), [
            'name' => 'Updated Team Name',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('hackathon_teams', [
            'id' => $team->id,
            'name' => 'Updated Team Name',
        ]);
    }

    public function test_authenticated_user_can_invite_member_to_team(): void
    {
        $user = $this->createAuthenticatedUser();
        $invitee = User::factory()->create();
        $hackathon = Hackathon::factory()->create([
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
        ]);
        $team = HackathonTeam::factory()->create([
            'hackathon_id' => $hackathon->id,
            'leader_id' => $user->id,
        ]);

        $response = $this->postJson($this->apiUrl("hackathons/teams/{$team->id}/invite"), [
            'user_ids' => [$invitee->id],
            'message' => 'Join our team!',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('hackathon_team_invitations', [
            'team_id' => $team->id,
            'invitee_id' => $invitee->id,
            'inviter_id' => $user->id,
        ]);
    }

    public function test_user_can_accept_team_invitation(): void
    {
        $user = $this->createAuthenticatedUser();
        $hackathon = Hackathon::factory()->create([
            'registration_deadline' => now()->addWeek(),
            'start_date' => now()->addWeeks(2),
        ]);
        $leader = User::factory()->create();
        $team = HackathonTeam::factory()->create([
            'hackathon_id' => $hackathon->id,
            'leader_id' => $leader->id,
        ]);
        $invitation = HackathonTeamInvitation::factory()->create([
            'team_id' => $team->id,
            'inviter_id' => $leader->id,
            'invitee_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->postJson($this->apiUrl("hackathons/invitations/{$invitation->id}/accept"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('hackathon_team_invitations', [
            'id' => $invitation->id,
            'status' => 'accepted',
        ]);
    }
}
