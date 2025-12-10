<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 30 siswa per tingkat kelas (6 tingkat × 30 = 180 siswa total)
        \App\Models\Siswa::factory()->count(180)->create();
    }
}
