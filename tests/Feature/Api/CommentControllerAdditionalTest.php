<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Post;

class CommentControllerAdditionalTest extends ApiTestCase
{
    public function test_authenticated_user_can_reply_to_comment(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();
        $parentComment = Comment::factory()->create(['post_id' => $post->id]);

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/comments"), [
            'content' => 'This is a reply',
            'parent_id' => $parentComment->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'parent_id' => $parentComment->id,
            'content' => 'This is a reply',
            'user_id' => $user->id,
        ]);
    }

    public function test_comment_max_length_validation(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create();

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/comments"), [
            'content' => str_repeat('a', 1001), // Exceeds max length
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
}
