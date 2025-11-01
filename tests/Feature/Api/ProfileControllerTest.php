<?php

namespace Tests\Feature\Api;

class ProfileControllerTest extends ApiTestCase
{
    public function test_authenticated_user_can_view_own_profile(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->getJson($this->apiUrl('profile'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                ],
            ])
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->putJson($this->apiUrl('profile'), [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $user->email,
            'bio' => 'Updated bio',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
            'last_name' => 'Name',
        ]);
    }

    public function test_authenticated_user_can_get_profile_progress(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->getJson($this->apiUrl('profile/progress'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'percentage',
                    'is_complete',
                ],
            ]);
    }

    public function test_authenticated_user_can_complete_profile(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson($this->apiUrl('profile/complete'));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile_completed' => true,
        ]);
    }
}
