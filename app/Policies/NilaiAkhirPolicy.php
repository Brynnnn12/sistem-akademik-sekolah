<?php

namespace App\Policies;

use App\Models\NilaiAkhir;
use App\Models\User;
use App\Models\PenugasanMengajar;
use Illuminate\Auth\Access\Response;

class NilaiAkhirPolicy
{
    /**
     * Determine whether the user can view any nilai akhir.
     */
    public function viewAny(User $user): bool
    {
        // Guru bisa melihat nilai akhir untuk mata pelajaran yang mereka ajar
        return $user->hasRole('Guru') || $user->hasRole('Admin') || $user->hasRole('KepalaSekolah');
    }

    /**
     * Determine whether the user can view nilai akhir untuk mata pelajaran tertentu.
     */
    public function view(User $user, NilaiAkhir $nilaiAkhir): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('KepalaSekolah')) {
            return true;
        }

        if ($user->hasRole('Guru')) {
            // Cek apakah guru mengajar mata pelajaran ini
            return PenugasanMengajar::where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $nilaiAkhir->mata_pelajaran_id)
                ->where('tahun_ajaran_id', $nilaiAkhir->tahun_ajaran_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can generate nilai akhir.
     */
    public function generate(User $user, $mataPelajaranId, $tahunAjaranId): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('KepalaSekolah')) {
            return true;
        }

        if ($user->hasRole('Guru')) {
            // Cek apakah guru mengajar mata pelajaran ini
            return PenugasanMengajar::where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $mataPelajaranId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view rekap nilai akhir sebagai wali kelas.
     */
    public function rekapWaliKelas(User $user): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('KepalaSekolah')) {
            return true;
        }

        if ($user->hasRole('Guru')) {
            // Cek apakah user adalah wali kelas
            return \App\Models\Kelas::where('wali_kelas_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Sistem otomatis, tidak ada create manual
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NilaiAkhir $nilaiAkhir): bool
    {
        return false; // Sistem otomatis, tidak ada update manual
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NilaiAkhir $nilaiAkhir): bool
    {
        return false; // Sistem otomatis, tidak ada delete manual
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NilaiAkhir $nilaiAkhir): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NilaiAkhir $nilaiAkhir): bool
    {
        return false;
    }
}
