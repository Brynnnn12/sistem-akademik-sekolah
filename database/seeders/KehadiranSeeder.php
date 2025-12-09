<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generate kehadiran data untuk 1 bulan terakhir
     */
    public function run(): void
    {
        $this->command->info('Seeding Kehadiran data...');

        // Get semua kelas dan siswa
        $kelasList = Kelas::with('siswas')->get();

        if ($kelasList->isEmpty()) {
            $this->command->warn('Tidak ada kelas ditemukan. Jalankan KelasSeeder terlebih dahulu.');
            return;
        }

        // Generate untuk 20 hari kerja terakhir (1 bulan)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $totalRecords = 0;

        foreach ($kelasList as $kelas) {
            if ($kelas->siswas->isEmpty()) {
                continue;
            }

            // Loop setiap hari kerja (skip weekend)
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                // Skip weekend
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Generate kehadiran untuk semua siswa di kelas ini
                foreach ($kelas->siswas as $siswa) {
                    // Random weighted status (80% hadir, 10% sakit, 5% izin, 5% alpha)
                    $random = rand(1, 100);

                    if ($random <= 80) {
                        $status = 'H';
                        $catatan = null;
                    } elseif ($random <= 90) {
                        $status = 'S';
                        $catatan = $this->getRandomSakitCatatan();
                    } elseif ($random <= 95) {
                        $status = 'I';
                        $catatan = $this->getRandomIzinCatatan();
                    } else {
                        $status = 'A';
                        $catatan = null;
                    }

                    Kehadiran::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelas->id,
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'status' => $status,
                        'catatan' => $catatan,
                    ]);

                    $totalRecords++;
                }

                $currentDate->addDay();
            }

            $this->command->info("✓ Generated kehadiran for {$kelas->nama}");
        }

        $this->command->info("✓ Total {$totalRecords} kehadiran records created!");
    }

    /**
     * Get random catatan untuk status Sakit
     */
    private function getRandomSakitCatatan(): ?string
    {
        $options = [
            'Demam',
            'Flu',
            'Batuk pilek',
            'Sakit perut',
            'Sakit kepala',
            'Demam tinggi',
            'Diare',
            null, // 30% tanpa catatan
        ];

        return $options[array_rand($options)];
    }

    /**
     * Get random catatan untuk status Izin
     */
    private function getRandomIzinCatatan(): ?string
    {
        $options = [
            'Keperluan keluarga',
            'Acara keluarga',
            'Ke dokter',
            'Urusan penting',
            'Kondangan',
            null, // 30% tanpa catatan
        ];

        return $options[array_rand($options)];
    }
}
