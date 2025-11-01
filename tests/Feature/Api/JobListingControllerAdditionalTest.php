<?php

namespace Tests\Feature\Api;

use App\Models\JobApplication;
use App\Models\JobListing;

class JobListingControllerAdditionalTest extends ApiTestCase
{
    public function test_admin_can_close_job_listing(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create(['status' => 'active']);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson($this->apiUrl("jobs/{$job->id}/close"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'status' => 'closed',
        ]);
    }

    public function test_admin_can_reopen_job_listing(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create(['status' => 'closed']);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson($this->apiUrl("jobs/{$job->id}/reopen"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_review_job_application(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create();
        $application = JobApplication::factory()->create([
            'job_listing_id' => $job->id,
            'status' => \App\JobApplicationStatus::PENDING,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson($this->apiUrl("jobs/applications/{$application->id}/review"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => \App\JobApplicationStatus::REVIEWED->value,
        ]);
    }

    public function test_admin_can_accept_job_application(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create();
        $application = JobApplication::factory()->create([
            'job_listing_id' => $job->id,
            'status' => \App\JobApplicationStatus::REVIEWED,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson($this->apiUrl("jobs/applications/{$application->id}/accept"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => \App\JobApplicationStatus::ACCEPTED->value,
        ]);
    }

    public function test_admin_can_reject_job_application(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create();
        $application = JobApplication::factory()->create([
            'job_listing_id' => $job->id,
            'status' => \App\JobApplicationStatus::REVIEWED,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson($this->apiUrl("jobs/applications/{$application->id}/reject"));

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => \App\JobApplicationStatus::REJECTED->value,
        ]);
    }

    public function test_admin_can_view_job_applications(): void
    {
        $admin = $this->createAdminUser();
        $job = JobListing::factory()->create();
        JobApplication::factory()->count(5)->create(['job_listing_id' => $job->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson($this->apiUrl("jobs/{$job->id}/applications"));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta',
            ]);
        $response->assertJsonCount(5, 'data');
    }
}
