<?php

namespace App\Policies;

use App\Models\KomponenNilai;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KomponenNilaiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Guru bisa lihat komponen nilai yang dia buat
        return $user->hasRole(['Guru', 'Admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KomponenNilai $komponenNilai): bool
    {
        // Guru hanya bisa lihat komponen nilai di kelas yang diajar
        if ($user->hasRole('Admin')) {
            return true;
        }

        return $komponenNilai->penugasan_mengajar->guru_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Guru bisa buat komponen nilai
        return $user->hasRole(['Guru', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KomponenNilai $komponenNilai): bool
    {
        // Guru hanya bisa update komponen nilai yang dia buat
        if ($user->hasRole('Admin')) {
            return true;
        }

        return $komponenNilai->penugasan_mengajar->guru_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KomponenNilai $komponenNilai): bool
    {
        // Guru hanya bisa hapus komponen nilai yang dia buat
        if ($user->hasRole('Admin')) {
            return true;
        }

        return $komponenNilai->penugasan_mengajar->guru_id === $user->id;
    }

    /**
     * Determine whether the user can manage nilai siswa untuk komponen ini.
     */
    public function manageNilai(User $user, KomponenNilai $komponenNilai): bool
    {
        // Guru hanya bisa manage nilai di komponen yang dia buat
        if ($user->hasRole('Admin')) {
            return true;
        }

        return $komponenNilai->penugasan_mengajar->guru_id === $user->id;
    }
}
