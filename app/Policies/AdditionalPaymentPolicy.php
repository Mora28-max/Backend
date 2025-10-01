<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Payments\AdditionalPayment;

class AdditionalPaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdditionalPayment $additionalPayment): bool
    {
        return $user->can('view payments');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('collect monthly charges');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdditionalPayment $additionalPayment): bool
    {
        return $user->can('collect monthly charges');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdditionalPayment $additionalPayment): bool
    {
        return $user->can('delete payments');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdditionalPayment $additionalPayment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdditionalPayment $additionalPayment): bool
    {
        return false;
    }
}
