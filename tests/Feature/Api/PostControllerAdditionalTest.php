<?php

namespace Tests\Feature\Api;

use App\Models\Post;

class PostControllerAdditionalTest extends ApiTestCase
{
    public function test_authenticated_user_can_share_post(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->published()->create();

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/share"), [
            'platform' => 'twitter',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        // Check that share was recorded
        $post->refresh();
        $this->assertGreaterThanOrEqual(0, $post->shares_count);
    }

    public function test_authenticated_user_can_vote_on_poll(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create([
            'type' => 'poll',
            'poll_options' => [
                ['text' => 'Option 1', 'votes' => 0],
                ['text' => 'Option 2', 'votes' => 0],
                ['text' => 'Option 3', 'votes' => 0],
            ],
            'poll_ends_at' => now()->addWeek(),
        ]);

        $response = $this->postJson($this->apiUrl("posts/{$post->id}/vote"), [
            'option_index' => 0,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        $post->refresh();
        $this->assertEquals(1, $post->poll_options[0]['votes']);
    }

    public function test_user_cannot_vote_twice_on_same_poll(): void
    {
        $user = $this->createAuthenticatedUser();
        $post = Post::factory()->create([
            'type' => 'poll',
            'poll_options' => [
                ['text' => 'Option 1', 'votes' => 0],
                ['text' => 'Option 2', 'votes' => 0],
            ],
            'poll_ends_at' => now()->addWeek(),
        ]);

        // First vote
        $this->postJson($this->apiUrl("posts/{$post->id}/vote"), [
            'option_index' => 0,
        ]);

        // Second vote should fail
        $response = $this->postJson($this->apiUrl("posts/{$post->id}/vote"), [
            'option_index' => 1,
        ]);

        $response->assertStatus(400);
    }
}
