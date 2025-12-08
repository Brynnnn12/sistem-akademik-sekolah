<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaranData = [
            [
                'nama' => '2024/2025',
                'semester' => 'ganjil',
                'aktif' => false,
            ],
            [
                'nama' => '2024/2025',
                'semester' => 'genap',
                'aktif' => false,
            ],
            [
                'nama' => '2025/2026',
                'semester' => 'ganjil',
                'aktif' => true, // Tahun ajaran aktif
            ],
            [
                'nama' => '2025/2026',
                'semester' => 'genap',
                'aktif' => false,
            ],
        ];

        foreach ($tahunAjaranData as $data) {
            \App\Models\TahunAjaran::create($data);
        }
    }
}
