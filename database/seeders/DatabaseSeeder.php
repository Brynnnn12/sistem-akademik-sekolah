<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            GuruSeeder::class,
            ProfileSeeder::class,
            TahunAjaranSeeder::class,
            MataPelajaranSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            KelasSiswaSeeder::class,
            // Data lengkap untuk tahun ajaran 2024/2025
            TahunAjaran2024_2025Seeder::class,
            // Data minimal untuk tahun ajaran aktif (2025/2026)
            PenugasanMengajarSeeder::class,
            KomponenNilaiSeeder::class,
            // Tidak ada nilai siswa dan presensi untuk tahun ajaran aktif
        ]);
    }
}
