<?php

namespace Database\Seeders;

use App\Models\KomponenNilai;
use App\Models\PenugasanMengajar;
use Illuminate\Database\Seeder;

class KomponenNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penugasanMengajars = PenugasanMengajar::all();

        foreach ($penugasanMengajars as $penugasan) {
            // Buat komponen nilai standar untuk setiap penugasan
            $komponenData = [
                [
                    'nama' => 'Ulangan Harian 1',
                    'jenis' => 'uh',
                    'bobot' => 15,
                ],
                [
                    'nama' => 'Tugas 1',
                    'jenis' => 'tugas',
                    'bobot' => 20,
                ],
                [
                    'nama' => 'UTS',
                    'jenis' => 'uts',
                    'bobot' => 30,
                ],
                [
                    'nama' => 'UAS',
                    'jenis' => 'uas',
                    'bobot' => 35,
                ],
            ];

            foreach ($komponenData as $data) {
                KomponenNilai::firstOrCreate([
                    'penugasan_mengajar_id' => $penugasan->id,
                    'nama' => $data['nama'],
                ], $data);
            }
        }

        $this->command->info('Komponen nilai berhasil dibuat untuk semua penugasan mengajar.');
    }
}
