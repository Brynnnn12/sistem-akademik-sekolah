<?php

namespace App\Services;

use App\Models\KelasSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class PromotionService
{
    /**
     * Promote students to a new class and academic year.
     *
     * @param array $siswaIds
     * @param int $newKelasId
     * @param int $newTahunAjaranId
     * @return void
     */
    public function promoteStudents(array $siswaIds, int $newKelasId, int $newTahunAjaranId): void
    {
        DB::transaction(function () use ($siswaIds, $newKelasId, $newTahunAjaranId) {
            foreach ($siswaIds as $siswaId) {
                // INSERT data baru ke tabel pivot (kelas_siswas)
                // Data lama di tahun lalu TETAP ADA (sebagai history)
                KelasSiswa::create([
                    'siswa_id'        => $siswaId,
                    'kelas_id'        => $newKelasId,       // Kelas Baru (2A)
                    'tahun_ajaran_id' => $newTahunAjaranId, // Tahun Baru (2024)
                ]);
            }
        });
    }

    /**
     * Graduate students by updating their status.
     *
     * @param array $siswaIds
     * @return void
     */
    public function graduateStudents(array $siswaIds): void
    {
        DB::transaction(function () use ($siswaIds) {
            // Update tabel Master Siswa
            Siswa::whereIn('id', $siswaIds)->update([
                'status' => 'lulus',
                'tanggal_lulus' => now()
            ]);

            // PENTING:
            // Siswa yang statusnya 'lulus' TIDAK PERLU dimasukkan
            // ke tabel kelas_siswas untuk tahun ajaran baru.
        });
    }
}
