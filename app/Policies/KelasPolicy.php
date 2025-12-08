<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KelasPolicy
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
    public function view(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['Admin', 'Guru', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kelas $kelas): bool
    {
        // Admin and kepala sekolah can update any kelas
        if ($user->hasAnyRole(['Admin', 'KepalaSekolah'])) {
            return true;
        }

        // Guru can only update kelas where they are wali kelas
        if ($user->hasRole('Guru')) {
            return $kelas->wali_kelas_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kelas $kelas): bool
    {
        // Only admin and kepala sekolah can delete kelas
        return $user->hasAnyRole(['Admin', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['Admin', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kelas $kelas): bool
    {
        return $user->hasRole('Admin');
    }
}
