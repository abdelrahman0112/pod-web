<?php

namespace Tests\Feature\Api;

use App\Models\JobApplication;
use App\Models\JobListing;

class JobListingControllerTest extends ApiTestCase
{
    public function test_guest_can_view_jobs(): void
    {
        JobListing::factory()->count(5)->active()->acceptingApplications()->create();

        $response = $this->getJson($this->apiUrl('jobs'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
                'links',
            ]);
    }

    public function test_guest_can_view_single_job(): void
    {
        $job = JobListing::factory()->active()->create();

        $response = $this->getJson($this->apiUrl("jobs/{$job->id}"));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $job->id);
    }

    public function test_authenticated_user_can_apply_for_job(): void
    {
        $user = $this->createAuthenticatedUser();
        $job = JobListing::factory()->active()->acceptingApplications()->create();

        $response = $this->postJson($this->apiUrl("jobs/{$job->id}/apply"), [
            'cover_letter' => 'I am interested in this position',
            'additional_info' => 'Some additional information',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_apply_twice_for_same_job(): void
    {
        $user = $this->createAuthenticatedUser();
        $job = JobListing::factory()->active()->acceptingApplications()->create();
        JobApplication::factory()->create([
            'job_listing_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson($this->apiUrl("jobs/{$job->id}/apply"), [
            'cover_letter' => 'Second application',
        ]);

        $response->assertStatus(400);
    }

    public function test_admin_can_create_job_listing(): void
    {
        $user = $this->createClientUser();
        $category = \App\Models\Category::factory()->create();

        $response = $this->postJson($this->apiUrl('jobs'), [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a software engineer',
            'company_name' => 'Tech Company',
            'location_type' => 'remote',
            'location' => '',
            'salary_min' => 80000,
            'salary_max' => 120000,
            'required_skills' => ['PHP', 'Laravel'],
            'experience_level' => 'mid',
            'application_deadline' => now()->addMonth()->toISOString(),
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('job_listings', [
            'title' => 'Software Engineer',
            'posted_by' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_view_own_applications(): void
    {
        $user = $this->createAuthenticatedUser();
        JobApplication::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson($this->apiUrl('jobs/my-applications'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
            ]);
        $response->assertJsonCount(3, 'data');
    }
}
