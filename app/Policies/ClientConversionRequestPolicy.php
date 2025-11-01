<?php

namespace App\Policies;

use App\Models\ClientConversionRequest;
use App\Models\User;

class ClientConversionRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all requests
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientConversionRequest $clientConversionRequest): bool
    {
        // Admins can view any request
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only view their own requests
        return $clientConversionRequest->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Regular users and clients can create requests (though clients shouldn't need to)
        // Check if user already has a pending request or is already a client/admin
        if ($user->isClient() || $user->isAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClientConversionRequest $clientConversionRequest): bool
    {
        // Admins can update any request
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only update their own pending requests
        return $clientConversionRequest->user_id === $user->id && $clientConversionRequest->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientConversionRequest $clientConversionRequest): bool
    {
        // Admins can delete any request
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only delete their own pending requests
        return $clientConversionRequest->user_id === $user->id && $clientConversionRequest->isPending();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClientConversionRequest $clientConversionRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClientConversionRequest $clientConversionRequest): bool
    {
        return $user->isSuperAdmin();
    }
}
