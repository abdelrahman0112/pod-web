<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Create and authenticate a user.
     */
    protected function createAuthenticatedUser(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ], $attributes));

        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /**
     * Create an admin user.
     */
    protected function createAdminUser(array $attributes = []): User
    {
        return $this->createAuthenticatedUser(array_merge([
            'role' => 'admin',
        ], $attributes));
    }

    /**
     * Create a super admin user.
     */
    protected function createSuperAdminUser(array $attributes = []): User
    {
        return $this->createAuthenticatedUser(array_merge([
            'role' => 'superadmin',
        ], $attributes));
    }

    /**
     * Create a client user.
     */
    protected function createClientUser(array $attributes = []): User
    {
        return $this->createAuthenticatedUser(array_merge([
            'role' => 'client',
        ], $attributes));
    }

    /**
     * Assert a successful API response.
     */
    protected function assertApiSuccess($response, ?string $message = null): void
    {
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => true,
            ]);

        if ($message) {
            $response->assertJsonFragment(['message' => $message]);
        }
    }

    /**
     * Assert an API error response.
     */
    protected function assertApiError($response, int $status = 400, ?string $message = null): void
    {
        $response->assertStatus($status)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => false,
            ]);

        if ($message) {
            $response->assertJsonFragment(['message' => $message]);
        }
    }

    /**
     * Get API base URL.
     */
    protected function apiUrl(string $path = ''): string
    {
        return '/api/v1'.($path ? '/'.$path : '');
    }
}
