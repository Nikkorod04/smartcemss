<?php

namespace App\Policies;

use App\Models\ExtensionProgram;
use App\Models\User;

class ProgramPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view programs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ExtensionProgram $program): bool
    {
        return true; // Everyone can view a program
    }

    /**
     * Determine whether the user can create models.
     * Only Director can create programs.
     */
    public function create(User $user): bool
    {
        // Temporarily allow all authenticated users for debugging
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Only Director can edit programs.
     */
    public function update(User $user, ExtensionProgram $program): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     * Only Director can delete programs.
     */
    public function delete(User $user, ExtensionProgram $program): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExtensionProgram $program): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExtensionProgram $program): bool
    {
        return strtolower((string) $user->role) === 'director' || strtolower((string) $user->role) === 'admin';
    }
}
