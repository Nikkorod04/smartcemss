<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view communities
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Community $community): bool
    {
        return true; // Everyone can view a community
    }

    /**
     * Determine whether the user can create models.
     * Only Director can create communities.
     */
    public function create(User $user): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     * Only Director can edit communities.
     */
    public function update(User $user, Community $community): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     * Only Director can delete communities.
     */
    public function delete(User $user, Community $community): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Community $community): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Community $community): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }
}
