<?php

namespace Database\Seeders;

use App\Models\JadwalMengajar;
use App\Models\PenugasanMengajar;
use Illuminate\Database\Seeder;

class JadwalMengajarSeeder extends Seeder
{
    public function run(): void
    {
        $penugasanList = PenugasanMengajar::aktif()->get();

        if ($penugasanList->isEmpty()) {
            $this->command->warn('Tidak ada penugasan mengajar aktif.');
            return;
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamMulaiOptions = ['07:00', '08:30', '10:00', '11:30', '13:00'];

        foreach ($penugasanList as $index => $penugasan) {
            // Buat 2 jadwal per penugasan (misal: Senin & Rabu, atau Selasa & Kamis)
            $hariIndex = $index % count($hariList);
            $jamIndex = $index % count($jamMulaiOptions);

            // Jadwal 1
            $hari1 = $hariList[$hariIndex];
            $jamMulai1 = $jamMulaiOptions[$jamIndex];
            $jamSelesai1 = date('H:i', strtotime($jamMulai1) + (90 * 60)); // +1.5 jam

            JadwalMengajar::create([
                'penugasan_mengajar_id' => $penugasan->id,
                'hari' => $hari1,
                'jam_mulai' => $jamMulai1,
                'jam_selesai' => $jamSelesai1,
            ]);

            $this->command->info("✓ Jadwal: {$hari1} {$jamMulai1}-{$jamSelesai1} - {$penugasan->mataPelajaran->nama} - {$penugasan->kelas->nama_lengkap}");

            // Jadwal 2 (hari berbeda)
            $hari2Index = ($hariIndex + 2) % count($hariList);
            $hari2 = $hariList[$hari2Index];
            $jamMulai2 = $jamMulaiOptions[($jamIndex + 1) % count($jamMulaiOptions)];
            $jamSelesai2 = date('H:i', strtotime($jamMulai2) + (90 * 60));

            JadwalMengajar::create([
                'penugasan_mengajar_id' => $penugasan->id,
                'hari' => $hari2,
                'jam_mulai' => $jamMulai2,
                'jam_selesai' => $jamSelesai2,
            ]);

            $this->command->info("✓ Jadwal: {$hari2} {$jamMulai2}-{$jamSelesai2} - {$penugasan->mataPelajaran->nama} - {$penugasan->kelas->nama_lengkap}");
        }

        $this->command->info('✓ JadwalMengajarSeeder selesai!');
    }
}
