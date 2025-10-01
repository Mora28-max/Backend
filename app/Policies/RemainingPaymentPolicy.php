<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Payments\RemainingPayment;

class RemainingPaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view remaining payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RemainingPayment $remainingPayments): bool
    {
        return $user->can('view remaining payments');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create remaining payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RemainingPayment $remainingPayments): bool
    {
        return $user->can('update remaining payments');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RemainingPayment $remainingPayments): bool
    {
        return $user->can('delete remaining payments');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RemainingPayment $remainingPayments): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RemainingPayment $remainingPayments): bool
    {
        return false;
    }

    public function pay(User $user, RemainingPayment $remainingPayments): bool
    {
        return $user->can('update remaining payments');
    }
}
