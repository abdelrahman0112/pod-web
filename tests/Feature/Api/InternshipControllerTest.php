<?php

namespace Tests\Feature\Api;

use App\Models\Internship;
use App\Models\InternshipApplication;

class InternshipControllerTest extends ApiTestCase
{
    public function test_guest_can_view_internships(): void
    {
        Internship::factory()->count(5)->create(['status' => 'open']);

        $response = $this->getJson($this->apiUrl('internships'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ]);
    }

    public function test_guest_can_view_single_internship(): void
    {
        $internship = Internship::factory()->create(['status' => 'open']);

        $response = $this->getJson($this->apiUrl("internships/{$internship->id}"));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $internship->id);
    }

    public function test_authenticated_user_can_apply_for_internship(): void
    {
        $user = $this->createAuthenticatedUser();
        $internship = Internship::factory()->create([
            'status' => 'open',
            'application_deadline' => now()->addWeek()->format('Y-m-d'),
        ]);

        $response = $this->postJson($this->apiUrl('internships/apply'), [
            'internship_id' => $internship->id,
            'cover_letter' => 'I am very interested in this internship',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('internship_applications', [
            'internship_id' => $internship->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_view_own_applications(): void
    {
        $user = $this->createAuthenticatedUser();
        InternshipApplication::factory()->count(3)->create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
        ]);

        $response = $this->getJson($this->apiUrl('internships/my-applications'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
            ]);
        $response->assertJsonCount(3, 'data');
    }
}
