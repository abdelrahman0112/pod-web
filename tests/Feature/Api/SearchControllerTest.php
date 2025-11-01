<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\JobListing;
use App\Models\Post;
use App\Models\User;

class SearchControllerTest extends ApiTestCase
{
    public function test_authenticated_user_can_search_all_content(): void
    {
        $user = $this->createAuthenticatedUser();
        Post::factory()->published()->create(['content' => 'Test post about Laravel']);
        Event::factory()->active()->create(['title' => 'Laravel Conference']);
        JobListing::factory()->active()->create(['title' => 'Laravel Developer']);

        $response = $this->getJson($this->apiUrl('search?q=Laravel&type=all'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'results',
                    'total',
                    'query',
                    'type',
                ],
            ]);
    }

    public function test_authenticated_user_can_search_posts(): void
    {
        $user = $this->createAuthenticatedUser();
        Post::factory()->published()->create(['content' => 'Test post']);
        Post::factory()->published()->create(['content' => 'Another post']);

        $response = $this->getJson($this->apiUrl('search/posts?q=Test'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_authenticated_user_can_search_events(): void
    {
        $user = $this->createAuthenticatedUser();
        Event::factory()->active()->create(['title' => 'Tech Conference']);

        $response = $this->getJson($this->apiUrl('search/events?q=Tech'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_authenticated_user_can_search_jobs(): void
    {
        $user = $this->createAuthenticatedUser();
        JobListing::factory()->active()->create(['title' => 'Senior Developer']);

        $response = $this->getJson($this->apiUrl('search/jobs?q=Developer'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_authenticated_user_can_search_users(): void
    {
        $user = $this->createAuthenticatedUser();
        User::factory()->create(['first_name' => 'John', 'last_name' => 'Developer']);

        $response = $this->getJson($this->apiUrl('search/users?q=John'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }
}
