<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarFunctionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_shows_avatar_upload_form(): void
    {
        $user = User::factory()->create(['profile_completed' => true]);

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertSee('Upload New Photo');
        $response->assertSee('name="avatar"', false);
        $response->assertSee('accept="image/jpeg,image/jpg,image/png,image/webp"', false);
        $response->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_avatar_upload_form_submission_works(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'profile_completed' => true,
        ]);

        $avatar = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put('/profile', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => $user->email,
                'avatar' => $avatar,
            ]);

        // After profile update, if profile becomes complete, it may redirect to home
        // So we just check for a successful redirect with success message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertTrue(str_contains($user->avatar, '/storage/avatars/'));

        // Verify the file was stored
        $avatarFilename = basename($user->avatar);
        Storage::disk('public')->assertExists("avatars/{$avatarFilename}");
    }

    public function test_profile_page_displays_uploaded_avatar(): void
    {
        $user = User::factory()->create([
            'avatar' => '/storage/avatars/test-avatar.jpg',
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('/storage/avatars/test-avatar.jpg');
    }
}
