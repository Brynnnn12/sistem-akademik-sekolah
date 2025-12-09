<?php

namespace Database\Seeders;

use App\Models\PenugasanMengajar;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class PenugasanMengajarSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        if (!$tahunAjaranAktif) {
            $this->command->warn('Tidak ada tahun ajaran aktif.');
            return;
        }

        $guru = User::role('Guru')->first();
        if (!$guru) {
            $this->command->warn('Tidak ada user dengan role Guru.');
            return;
        }

        $kelas = Kelas::first();
        if (!$kelas) {
            $this->command->warn('Tidak ada data kelas.');
            return;
        }

        $mataPelajaran = MataPelajaran::first();
        if (!$mataPelajaran) {
            $this->command->warn('Tidak ada data mata pelajaran.');
            return;
        }

        // Buat beberapa penugasan mengajar
        $allKelas = Kelas::take(3)->get();
        $allMapel = MataPelajaran::take(4)->get();

        foreach ($allKelas as $kelas) {
            foreach ($allMapel->take(2) as $mapel) {
                PenugasanMengajar::firstOrCreate([
                    'guru_id' => $guru->id,
                    'kelas_id' => $kelas->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);

                $this->command->info("✓ Penugasan: {$guru->name} - {$mapel->nama} - {$kelas->nama_lengkap}");
            }
        }

        $this->command->info('✓ PenugasanMengajarSeeder selesai!');
    }
}
