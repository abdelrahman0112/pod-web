<?php

namespace App\Policies;

use App\Models\Hackathon;
use App\Models\User;

class HackathonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view hackathons
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hackathon $hackathon): bool
    {
        return true; // All authenticated users can view individual hackathons
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow superadmin, admin, and client roles to create hackathons
        return in_array($user->role, ['superadmin', 'admin', 'client']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hackathon $hackathon): bool
    {
        // Allow superadmin and admin to update any hackathon
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Allow hackathon creator to update their own hackathon
        return $hackathon->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hackathon $hackathon): bool
    {
        // Allow superadmin and admin to delete any hackathon
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Allow hackathon creator to delete their own hackathon
        return $hackathon->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hackathon $hackathon): bool
    {
        return in_array($user->role, ['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hackathon $hackathon): bool
    {
        return $user->role === 'superadmin';
    }
}
