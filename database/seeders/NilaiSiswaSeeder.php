<?php

namespace Database\Seeders;

use App\Models\KomponenNilai;
use App\Models\KelasSiswa;
use App\Models\NilaiSiswa;
use Illuminate\Database\Seeder;

class NilaiSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komponenNilais = KomponenNilai::all();

        foreach ($komponenNilais as $komponen) {
            // Ambil siswa yang ada di kelas penugasan ini
            $siswaIds = KelasSiswa::where('kelas_id', $komponen->penugasan_mengajar->kelas_id)
                ->where('tahun_ajaran_id', $komponen->penugasan_mengajar->tahun_ajaran_id)
                ->pluck('siswa_id');

            foreach ($siswaIds as $siswaId) {
                // Buat nilai random antara 70-100 untuk siswa ini
                $nilai = rand(70, 100);

                NilaiSiswa::firstOrCreate([
                    'komponen_nilai_id' => $komponen->id,
                    'siswa_id' => $siswaId,
                ], [
                    'nilai' => $nilai,
                ]);
            }
        }

        $this->command->info('Nilai siswa berhasil dibuat untuk semua komponen nilai.');
    }
}
