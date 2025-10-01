<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Agreements\PaymentAgreement;

class PaymentAgreementPolicy
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
    public function view(User $user, PaymentAgreement $paymentAgreement): bool
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
    public function update(User $user, PaymentAgreement $paymentAgreement): bool
    {
        return $user->can('collect monthly charges');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentAgreement $paymentAgreement): bool
    {
        return $user->can('collect monthly charges');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentAgreement $paymentAgreement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentAgreement $paymentAgreement): bool
    {
        return false;
    }

    public function payAgreement(User $user): bool
    {
        return $user->can('view monthly charges');
    }
}
