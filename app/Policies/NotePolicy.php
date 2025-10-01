<?php

namespace App\Policies;


use App\Models\User;
use App\Models\Reports\Note;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('reports view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Note $notes): bool
    {
        return $user->can('reports view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('reports create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $notes): bool
    {
        return $user->can('reports update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $notes): bool
    {
        return $user->can('reports delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Note $notes): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Notes $notes): bool
    {
        return false;
    }
}
