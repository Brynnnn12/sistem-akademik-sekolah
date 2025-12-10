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

        $guruList = User::role('Guru')->get();
        if ($guruList->isEmpty()) {
            $this->command->warn('Tidak ada user dengan role Guru.');
            return;
        }

        $kelasList = Kelas::all();
        $mataPelajaranList = MataPelajaran::all();

        if ($kelasList->isEmpty() || $mataPelajaranList->isEmpty()) {
            $this->command->warn('Tidak ada data kelas atau mata pelajaran.');
            return;
        }

        // Distribusi penugasan mengajar untuk semua guru
        $guruIndex = 0;
        foreach ($kelasList as $kelas) {
            foreach ($mataPelajaranList as $mapel) {
                // Assign guru secara bergantian
                $guru = $guruList[$guruIndex % $guruList->count()];

                PenugasanMengajar::firstOrCreate([
                    'guru_id' => $guru->id,
                    'kelas_id' => $kelas->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);

                $this->command->info("✓ Penugasan: {$guru->name} - {$mapel->nama} - {$kelas->nama_lengkap}");

                $guruIndex++;
            }
        }

        $this->command->info('✓ PenugasanMengajarSeeder selesai!');
    }
}
