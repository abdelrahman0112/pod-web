<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends ApiTestCase
{
    public function test_user_can_register(): void
    {
        $response = $this->postJson($this->apiUrl('auth/register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'email',
                        'first_name',
                        'last_name',
                    ],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson($this->apiUrl('auth/register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson($this->apiUrl('auth/register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Different123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
        ]);

        $response = $this->postJson($this->apiUrl('auth/login'), [
            'email' => 'john@example.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'email',
                    ],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson($this->apiUrl('auth/login'), [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->getJson($this->apiUrl('auth/me'));

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
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_unauthenticated_user_cannot_get_profile(): void
    {
        $response = $this->getJson($this->apiUrl('auth/me'));

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson($this->apiUrl('auth/logout'));

        $this->assertApiSuccess($response, 'Logged out successfully');
    }

    public function test_user_can_request_password_reset(): void
    {
        $user = User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson($this->apiUrl('auth/forgot-password'), [
            'email' => 'john@example.com',
        ]);

        $this->assertApiSuccess($response);
    }
}
