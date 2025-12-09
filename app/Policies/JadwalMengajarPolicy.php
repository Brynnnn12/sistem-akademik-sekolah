<?php

namespace App\Policies;

use App\Models\JadwalMengajar;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JadwalMengajarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Admin', 'Guru', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JadwalMengajar $jadwalMengajar): bool
    {
        return $user->hasRole(['Admin', 'Guru', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JadwalMengajar $jadwalMengajar): bool
    {
        // Admin bisa update semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa update jadwal mengajar miliknya sendiri
        if ($user->hasRole('Guru')) {
            return $jadwalMengajar->penugasanMengajar->guru_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JadwalMengajar $jadwalMengajar): bool
    {
        // Admin bisa delete semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa delete jadwal mengajar miliknya sendiri
        if ($user->hasRole('Guru')) {
            return $jadwalMengajar->penugasanMengajar->guru_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JadwalMengajar $jadwalMengajar): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JadwalMengajar $jadwalMengajar): bool
    {
        return $user->hasRole('Admin');
    }
}
