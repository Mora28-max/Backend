<?php

namespace App\Policies\Audit;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Audit\CashRegisterAudit;

class CashRegisterAuditPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('delete payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashRegisterAudit $cashRegisterAudit): bool
    {
        return $user->can('delete payments');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('delete payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashRegisterAudit $cashRegisterAudit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashRegisterAudit $cashRegisterAudit): bool
    {
        return $user->can('delete payments');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CashRegisterAudit $cashRegisterAudit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CashRegisterAudit $cashRegisterAudit): bool
    {
        return false;
    }
}
