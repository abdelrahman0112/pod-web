<?php

namespace Tests\Feature\Api;

use App\Models\User;

class AuthControllerAdditionalTest extends ApiTestCase
{
    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson($this->apiUrl('auth/register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_password_reset_requires_valid_email(): void
    {
        $response = $this->postJson($this->apiUrl('auth/forgot-password'), [
            'email' => 'nonexistent@example.com',
        ]);

        // Should still return success for security (don't reveal if email exists)
        $response->assertStatus(200);
    }

    public function test_refresh_token_requires_authentication(): void
    {
        $response = $this->postJson($this->apiUrl('auth/refresh'));

        $response->assertStatus(401);
    }
}
