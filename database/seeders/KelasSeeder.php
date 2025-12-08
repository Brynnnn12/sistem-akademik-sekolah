<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasData = [
            ['nama' => 'A', 'tingkat_kelas' => 1],
            ['nama' => 'B', 'tingkat_kelas' => 1],
            ['nama' => 'A', 'tingkat_kelas' => 2],
            ['nama' => 'B', 'tingkat_kelas' => 2],
            ['nama' => 'A', 'tingkat_kelas' => 3],
            ['nama' => 'B', 'tingkat_kelas' => 3],
            ['nama' => 'A', 'tingkat_kelas' => 4],
            ['nama' => 'B', 'tingkat_kelas' => 4],
            ['nama' => 'A', 'tingkat_kelas' => 5],
            ['nama' => 'B', 'tingkat_kelas' => 5],
            ['nama' => 'A', 'tingkat_kelas' => 6],
            ['nama' => 'B', 'tingkat_kelas' => 6],
        ];

        foreach ($kelasData as $data) {
            \App\Models\Kelas::factory()->create($data);
        }
    }
}
