<?php

namespace App\Policies;

use App\Models\NilaiSiswa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NilaiSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Guru bisa lihat nilai siswa
        return $user->hasRole(['Guru', 'Admin', 'KepalaSekolah']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NilaiSiswa $nilaiSiswa): bool
    {
        // Admin bisa lihat semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa lihat nilai di komponen yang dia buat
        if ($user->hasRole('Guru')) {
            return $nilaiSiswa->komponen_nilai->penugasan_mengajar->guru_id === $user->id;
        }

        // Wali kelas bisa lihat nilai siswa di kelasnya
        if ($user->hasRole('KepalaSekolah')) {
            return true; // Kepsek bisa lihat semua
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya guru yang bisa input nilai
        return $user->hasRole(['Guru', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NilaiSiswa $nilaiSiswa): bool
    {
        // Admin bisa update semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa update nilai di komponen yang dia buat
        if ($user->hasRole('Guru')) {
            return $nilaiSiswa->komponen_nilai->penugasan_mengajar->guru_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NilaiSiswa $nilaiSiswa): bool
    {
        // Admin bisa hapus semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa hapus nilai di komponen yang dia buat
        if ($user->hasRole('Guru')) {
            return $nilaiSiswa->komponen_nilai->penugasan_mengajar->guru_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk update nilai siswa.
     */
    public function bulkUpdate(User $user, int $komponenNilaiId): bool
    {
        // Admin bisa bulk update semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Guru hanya bisa bulk update di komponen yang dia buat
        if ($user->hasRole('Guru')) {
            $komponen = \App\Models\KomponenNilai::find($komponenNilaiId);
            return $komponen && $komponen->penugasan_mengajar->guru_id === $user->id;
        }

        return false;
    }
}
