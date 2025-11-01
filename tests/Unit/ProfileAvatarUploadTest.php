<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        // Create a user with complete profile
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'city' => 'Test City',
            'country' => 'Test Country',
            'gender' => 'male',
            'bio' => 'Test bio',
            'skills' => ['PHP', 'Laravel'],
            'experience_level' => 'entry',
            'profile_completed' => true,
        ]);

        // Create a fake image file
        $file = UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(500);

        // Act as the authenticated user
        $this->actingAs($user);

        // Submit the profile update with avatar
        $response = $this->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => $user->email, // Use the same email to avoid unique validation
                'avatar' => $file,
            ]);

        // Assert the response is successful
        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profile updated successfully!');

        // Refresh the user model
        $user->refresh();

        // Assert the avatar was uploaded and saved
        $this->assertNotNull($user->avatar);
        $this->assertTrue(str_contains($user->avatar, '/storage/avatars/'));

        // Assert the file was actually stored
        $avatarFilename = basename($user->avatar);
        Storage::disk('public')->assertExists("avatars/{$avatarFilename}");
    }

    public function test_avatar_upload_validates_file_size(): void
    {
        // Create a user with complete profile
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'profile_completed' => true,
        ]);

        // Create a file that's too large (3MB)
        $file = UploadedFile::fake()->image('large-avatar.jpg', 1000, 1000)->size(3072);

        // Act as the authenticated user
        $this->actingAs($user);

        // Submit the profile update with oversized avatar
        $response = $this->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => $user->email,
                'avatar' => $file,
            ]);

        // Assert validation failed
        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_validates_file_type(): void
    {
        // Create a user with complete profile
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'profile_completed' => true,
        ]);

        // Create a non-image file
        $file = UploadedFile::fake()->create('document.pdf', 500);

        // Act as the authenticated user
        $this->actingAs($user);

        // Submit the profile update with invalid file type
        $response = $this->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => $user->email,
                'avatar' => $file,
            ]);

        // Assert validation failed
        $response->assertSessionHasErrors('avatar');
    }
}
