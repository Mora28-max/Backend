<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Readings\MeterReadingSchedule;

class MeterReadingSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('notifications view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MeterReadingSchedule $meterReadingSchedule): bool
    {
        return $user->can('notifications view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('notifications create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MeterReadingSchedule $meterReadingSchedule): bool
    {
        return $user->can('notifications update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MeterReadingSchedule $meterReadingSchedule): bool
    {
        return $user->can('notifications update');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MeterReadingSchedule $meterReadingSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MeterReadingSchedule $meterReadingSchedule): bool
    {
        return false;
    }
}
