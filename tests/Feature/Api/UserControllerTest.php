<?php

namespace Tests\Feature\Api;

use App\Models\User;

class UserControllerTest extends ApiTestCase
{
    public function test_unauthenticated_user_can_view_users(): void
    {
        User::factory()->count(5)->create();

        $response = $this->getJson($this->apiUrl('users'));

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_view_users(): void
    {
        $user = $this->createAuthenticatedUser();
        User::factory()->count(5)->create();

        $response = $this->getJson($this->apiUrl('users'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ])
            ->assertJson([
                'success' => true,
            ]);
        $response->assertJsonCount(6, 'data'); // 5 created + authenticated user
    }

    public function test_authenticated_user_can_view_single_user(): void
    {
        $viewer = $this->createAuthenticatedUser();
        $user = User::factory()->create();

        $response = $this->getJson($this->apiUrl("users/{$user->id}"));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_authenticated_user_can_search_users(): void
    {
        $user = $this->createAuthenticatedUser();
        User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

        $response = $this->getJson($this->apiUrl('users/search?q=John'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'users',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);
        $response->assertJsonCount(1, 'data.users');
    }

    public function test_search_returns_empty_for_no_matches(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->getJson($this->apiUrl('users/search?q=NonexistentUser123'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'users',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);
        $response->assertJsonCount(0, 'data.users');
    }
}
