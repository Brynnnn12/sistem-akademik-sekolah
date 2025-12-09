<?php

namespace Database\Seeders;

use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaranAktif = TahunAjaran::aktif()->first();

        if (!$tahunAjaranAktif) {
            $this->command->warn('Tidak ada tahun ajaran aktif.');
            return;
        }

        $siswaList = Siswa::take(30)->get();
        $kelasList = Kelas::take(3)->get();

        if ($siswaList->isEmpty() || $kelasList->isEmpty()) {
            $this->command->warn('Tidak ada data siswa atau kelas.');
            return;
        }

        $siswaPerKelas = $siswaList->chunk(10);

        foreach ($kelasList as $index => $kelas) {
            $siswaChunk = $siswaPerKelas[$index] ?? collect();

            foreach ($siswaChunk as $siswa) {
                KelasSiswa::firstOrCreate([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                ]);

                $this->command->info("✓ Siswa {$siswa->nama} ditambahkan ke {$kelas->nama_lengkap}");
            }
        }

        $this->command->info('✓ KelasSiswaSeeder selesai!');
    }
}
