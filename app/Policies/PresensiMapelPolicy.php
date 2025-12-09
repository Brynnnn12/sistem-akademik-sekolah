<?php

namespace App\Policies;

use App\Models\PresensiMapel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PresensiMapelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Guru', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PresensiMapel $presensiMapel): bool
    {
        // Admin and KepalaSekolah can view all, Guru can only view their own
        return $user->hasAnyRole(['Admin', 'KepalaSekolah']) ||
            ($user->hasRole('Guru') && $presensiMapel->guru_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Guru can create presensi
        return $user->hasRole('Guru');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PresensiMapel $presensiMapel): bool
    {
        // Only the Guru who created the presensi can update it
        return $user->hasRole('Guru') && $presensiMapel->guru_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PresensiMapel $presensiMapel): bool
    {
        // Admin or the Guru who created it can delete
        return $user->hasRole('Admin') ||
            ($user->hasRole('Guru') && $presensiMapel->guru_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PresensiMapel $presensiMapel): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PresensiMapel $presensiMapel): bool
    {
        return $user->hasRole('Admin');
    }
}
