<?php

namespace App\Policies;

use App\Models\Customers\Customer;
use App\Models\Payments\MonthlyServiceCharge;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MonthlyServiceChargePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view monthly charges');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MonthlyServiceCharge $monthlyServiceCharge): bool
    {
        return $user->can('view monthly charges');
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
    public function update(User $user, MonthlyServiceCharge $monthlyServiceCharge): bool
    {
        return $user->can('customers update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MonthlyServiceCharge $monthlyServiceCharge): bool
    {
        return $user->can('customers update');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MonthlyServiceCharge $monthlyServiceCharge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MonthlyServiceCharge $monthlyServiceCharge): bool
    {
        return false;
    }

    public function showForgiven(Customer $customer): bool
    {
        return $customer->can('view monthly charges');
    }
}
