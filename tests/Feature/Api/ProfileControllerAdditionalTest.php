<?php

namespace Tests\Feature\Api;

use App\Models\Experience;
use App\Models\Portfolio;

class ProfileControllerAdditionalTest extends ApiTestCase
{
    public function test_authenticated_user_can_add_experience(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson($this->apiUrl('profile/experiences'), [
            'title' => 'Software Engineer',
            'company' => 'Tech Corp',
            'start_date' => '2020-01-01',
            'end_date' => '2022-12-31',
            'description' => 'Worked on web applications',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('experiences', [
            'user_id' => $user->id,
            'title' => 'Software Engineer',
        ]);
    }

    public function test_authenticated_user_can_update_experience(): void
    {
        $user = $this->createAuthenticatedUser();
        $experience = Experience::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson($this->apiUrl("profile/experiences/{$experience->id}"), [
            'title' => 'Senior Software Engineer',
            'company' => $experience->company,
            'start_date' => $experience->start_date->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'title' => 'Senior Software Engineer',
        ]);
    }

    public function test_authenticated_user_can_delete_experience(): void
    {
        $user = $this->createAuthenticatedUser();
        $experience = Experience::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson($this->apiUrl("profile/experiences/{$experience->id}"));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
    }

    public function test_authenticated_user_can_add_portfolio(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->postJson($this->apiUrl('profile/portfolios'), [
            'title' => 'My Awesome Project',
            'description' => 'A cool project',
            'url' => 'https://example.com/project',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('portfolios', [
            'user_id' => $user->id,
            'title' => 'My Awesome Project',
        ]);
    }

    public function test_authenticated_user_can_update_portfolio(): void
    {
        $user = $this->createAuthenticatedUser();
        $portfolio = Portfolio::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson($this->apiUrl("profile/portfolios/{$portfolio->id}"), [
            'title' => 'Updated Portfolio Title',
            'description' => $portfolio->description,
            'url' => $portfolio->url,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('portfolios', [
            'id' => $portfolio->id,
            'title' => 'Updated Portfolio Title',
        ]);
    }

    public function test_authenticated_user_can_delete_portfolio(): void
    {
        $user = $this->createAuthenticatedUser();
        $portfolio = Portfolio::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson($this->apiUrl("profile/portfolios/{$portfolio->id}"));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
    }
}
