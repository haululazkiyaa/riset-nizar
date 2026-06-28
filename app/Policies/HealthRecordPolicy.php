<?php

namespace App\Policies;

use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\User;

class HealthRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'doctor', 'nurse', 'auditor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        return $user->hasAnyRole(['admin', 'doctor', 'nurse', 'auditor']) || $patient->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Patient $patient): bool
    {
        return $user->hasAnyRole(['admin', 'doctor']) && $patient->created_by === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HealthRecord $healthRecord): bool
    {
        return $user->hasAnyRole(['admin', 'doctor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HealthRecord $healthRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HealthRecord $healthRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HealthRecord $healthRecord): bool
    {
        return false;
    }
}
