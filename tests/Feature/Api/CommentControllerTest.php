<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentControllerTest extends ApiTestCase
{
    public function test_authenticated_user_can_view_comments(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        Comment::factory()->count(5)->create(['post_id' => $post->id]);

        $response = $this->getJson($this->apiUrl("posts/{$post->id}/comments"));

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
        $response->assertJsonCount(5, 'data');
    }

    public function test_authenticated_user_can_create_comment(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/comments"), [
            'content' => 'This is a test comment',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_update_own_comment(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->putJson($this->apiUrl("comments/{$comment->id}"), [
            'content' => 'Updated comment content',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment content',
        ]);
    }

    public function test_user_cannot_update_other_users_comment(): void
    {
        $user = $this->createAuthenticatedUser();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->putJson($this->apiUrl("comments/{$comment->id}"), [
            'content' => 'Updated comment content',
        ]);

        $response->assertStatus(403);
    }

    public function test_authenticated_user_can_delete_own_comment(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson($this->apiUrl("comments/{$comment->id}"));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_comment_requires_content(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/comments"), [
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
}
