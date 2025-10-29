<?php

namespace App\Policies;

use App\Models\JobListing;
use App\Models\User;

class JobListingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view job listings
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobListing $jobListing): bool
    {
        return true; // All authenticated users can view individual job listings
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow all authenticated users to create job listings
        // In the future, you might want to add role-based restrictions
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobListing $jobListing): bool
    {
        // Only the job poster or admin/superadmin can update
        return $user->id === $jobListing->posted_by ||
               $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobListing $jobListing): bool
    {
        // Only the job poster or admin/superadmin can delete
        return $user->id === $jobListing->posted_by ||
               $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobListing $jobListing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobListing $jobListing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view applications for the job.
     */
    public function viewApplications(User $user, JobListing $jobListing): bool
    {
        // Only the job poster or admin/superadmin can view applications
        return $user->id === $jobListing->posted_by ||
               $user->hasAnyRole(['superadmin', 'admin']);
    }
}
