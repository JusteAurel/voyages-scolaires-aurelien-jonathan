<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voyage;
use Illuminate\Auth\Access\Response;

class VoyagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Voyage $voyage): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['enseignant', 'admin']);
    }

    public function update(User $user, Voyage $voyage): bool
    {
        return $user->role === 'admin' || $user->id === $voyage->user_id;
    }

    public function delete(User $user, Voyage $voyage): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Voyage $voyage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Voyage $voyage): bool
    {
        return false;
    }
}
