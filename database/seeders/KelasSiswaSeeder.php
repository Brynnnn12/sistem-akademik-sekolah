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
        $tahunAjaranList = TahunAjaran::all();

        if ($tahunAjaranList->isEmpty()) {
            $this->command->warn('Tidak ada data tahun ajaran.');
            return;
        }

        $siswaList = Siswa::all();
        $kelasList = Kelas::all();

        if ($siswaList->isEmpty() || $kelasList->isEmpty()) {
            $this->command->warn('Tidak ada data siswa atau kelas.');
            return;
        }

        foreach ($tahunAjaranList as $tahunAjaran) {
            $this->command->info("Memproses tahun ajaran: {$tahunAjaran->nama} {$tahunAjaran->semester}");

            // Distribusi siswa ke kelas berdasarkan tingkat
            $this->distributeSiswaToKelas($siswaList, $kelasList, $tahunAjaran);
        }

        $this->command->info('✓ KelasSiswaSeeder selesai!');
    }

    private function distributeSiswaToKelas($siswaList, $kelasList, $tahunAjaran): void
    {
        // Untuk tahun 2024/2025, distribusikan siswa ke semua tingkat kelas 1-6
        // Untuk tahun 2025/2026, distribusikan siswa ke semua tingkat kelas 1-6 (karena ini data testing)
        if (str_contains($tahunAjaran->nama, '2024/2025') || str_contains($tahunAjaran->nama, '2025/2026')) {
            // Tahun 2024/2025 dan 2025/2026: semua siswa ada di semua tingkat kelas untuk testing
            $this->distributeForTesting($siswaList, $kelasList, $tahunAjaran);
        }
    }

    private function distributeForTesting($siswaList, $kelasList, $tahunAjaran): void
    {
        // Distribusi 30 siswa per tingkat kelas (15 per kelas A/B) untuk testing
        $siswaPerTingkat = 30;
        $siswaIndex = 0;

        $this->command->info("Total siswa: " . $siswaList->count());
        $this->command->info("Total kelas: " . $kelasList->count());

        for ($tingkat = 1; $tingkat <= 6; $tingkat++) {
            $kelasTingkat = $kelasList->where('tingkat_kelas', $tingkat);
            $this->command->info("Tingkat {$tingkat}: {$kelasTingkat->count()} kelas");

            if ($kelasTingkat->isEmpty()) {
                $this->command->warn("Tidak ada kelas untuk tingkat {$tingkat}, skip");
                continue;
            }

            // Ambil 30 siswa untuk tingkat ini
            $siswaTingkat = $siswaList->slice($siswaIndex, $siswaPerTingkat);
            $this->command->info("Mengambil siswa index {$siswaIndex} sampai " . ($siswaIndex + $siswaPerTingkat - 1) . " untuk tingkat {$tingkat}");
            $siswaIndex += $siswaPerTingkat;

            // Distribusi ke kelas A dan B (15 siswa per kelas)
            $siswaA = $siswaTingkat->take(15);
            $siswaB = $siswaTingkat->skip(15)->take(15);

            $this->command->info("Kelas A mendapat {$siswaA->count()} siswa, Kelas B mendapat {$siswaB->count()} siswa");

            // Assign ke kelas A
            $kelasA = $kelasTingkat->where('nama', 'A')->first();
            if ($kelasA) {
                foreach ($siswaA as $siswa) {
                    KelasSiswa::firstOrCreate([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelasA->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ]);

                    $this->command->info("✓ Siswa {$siswa->nama} ditambahkan ke {$kelasA->nama_lengkap} ({$tahunAjaran->nama} {$tahunAjaran->semester})");
                }
            }

            // Assign ke kelas B
            $kelasB = $kelasTingkat->where('nama', 'B')->first();
            if ($kelasB) {
                foreach ($siswaB as $siswa) {
                    KelasSiswa::firstOrCreate([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelasB->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ]);

                    $this->command->info("✓ Siswa {$siswa->nama} ditambahkan ke {$kelasB->nama_lengkap} ({$tahunAjaran->nama} {$tahunAjaran->semester})");
                }
            }
        }
    }
}
