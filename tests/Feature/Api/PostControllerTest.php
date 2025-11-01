<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;

class PostControllerTest extends ApiTestCase
{
    public function test_guest_can_view_posts(): void
    {
        Post::factory()->count(5)->published()->create();

        $response = $this->getJson($this->apiUrl('posts'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_guest_can_view_single_post(): void
    {
        $post = Post::factory()->published()->create();

        $response = $this->getJson($this->apiUrl("posts/{$post->id}"));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $post->id);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson($this->apiUrl('posts'), [
            'content' => 'This is a test post',
            'type' => 'text',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('posts', [
            'content' => 'This is a test post',
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_update_own_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson($this->apiUrl("posts/{$post->id}"), [
            'content' => 'Updated content',
            'type' => 'text',
        ]);

        $this->assertApiSuccess($response, 'Post updated successfully');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => 'Updated content',
        ]);
    }

    public function test_user_cannot_update_other_users_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->putJson($this->apiUrl("posts/{$post->id}"), [
            'content' => 'Updated content',
            'type' => 'text',
        ]);

        $response->assertStatus(403);
    }

    public function test_authenticated_user_can_delete_own_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson($this->apiUrl("posts/{$post->id}"));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_authenticated_user_can_like_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/like"));

        $this->assertApiSuccess($response);

        $this->assertDatabaseHas('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_unlike_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $post->likes()->create(['user_id' => $user->id]);

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/like"));

        $this->assertApiSuccess($response);

        $this->assertDatabaseMissing('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }
}
