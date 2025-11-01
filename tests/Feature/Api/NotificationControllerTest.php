<?php

namespace Tests\Feature\Api;

class NotificationControllerTest extends ApiTestCase
{
    public function test_authenticated_user_can_view_notifications(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $liker = \App\Models\User::factory()->create();

        // Create some notifications
        $user->notify(new \App\Notifications\PostLiked($post, $liker));

        $response = $this->getJson($this->apiUrl('notifications'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ]);
    }

    public function test_authenticated_user_can_get_unread_count(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $liker = \App\Models\User::factory()->create();

        $user->notify(new \App\Notifications\PostLiked($post, $liker));

        $response = $this->getJson($this->apiUrl('notifications/unread-count'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'count',
                ],
            ])
            ->assertJsonPath('data.count', 1);
    }

    public function test_authenticated_user_can_mark_notification_as_read(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $liker = \App\Models\User::factory()->create();

        $user->notify(new \App\Notifications\PostLiked($post, $liker));

        $notification = $user->notifications()->first();

        $response = $this->patchJson($this->apiUrl("notifications/{$notification->id}/read"));

        $response->assertStatus(200);

        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_authenticated_user_can_mark_all_notifications_as_read(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $liker = \App\Models\User::factory()->create();

        $user->notify(new \App\Notifications\PostLiked($post, $liker));

        $response = $this->patchJson($this->apiUrl('notifications/read-all'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_delete_notification(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $liker = \App\Models\User::factory()->create();

        $user->notify(new \App\Notifications\PostLiked($post, $liker));

        $notification = $user->notifications()->first();
        $notificationId = $notification->id;

        $response = $this->deleteJson($this->apiUrl("notifications/{$notificationId}"));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('notifications', ['id' => $notificationId]);
    }
}
