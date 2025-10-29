<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view events
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return true; // All authenticated users can view individual events
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow superadmin, admin, and client roles to create events
        return in_array($user->role, ['superadmin', 'admin', 'client']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Allow superadmin and admin to update any event
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Allow event creator to update their own event
        return $event->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Allow superadmin and admin to delete any event
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return true;
        }

        // Allow event creator to delete their own event
        return $event->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
