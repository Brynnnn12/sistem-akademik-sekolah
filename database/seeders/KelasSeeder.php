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

        // Set wali kelas untuk beberapa kelas
        $this->setWaliKelas();
    }

    /**
     * Set wali kelas untuk beberapa kelas
     */
    private function setWaliKelas(): void
    {
        $guru = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Guru');
        })->get();

        if ($guru->isEmpty()) {
            return;
        }

        $kelas = \App\Models\Kelas::all();

        // Set wali kelas untuk kelas 1A, 2A, 3A, 4A, 5A, 6A
        $waliKelasMapping = [
            '1A' => $guru->first()->id ?? null,
            '2A' => $guru->skip(1)->first()->id ?? $guru->first()->id,
            '3A' => $guru->skip(2)->first()->id ?? $guru->first()->id,
            '4A' => $guru->skip(3)->first()->id ?? $guru->first()->id,
            '5A' => $guru->skip(4)->first()->id ?? $guru->first()->id,
            '6A' => $guru->skip(5)->first()->id ?? $guru->first()->id,
        ];

        foreach ($waliKelasMapping as $namaKelas => $waliId) {
            if ($waliId) {
                $kelasItem = $kelas->where('nama', substr($namaKelas, 1, 1))
                    ->where('tingkat_kelas', (int)substr($namaKelas, 0, 1))
                    ->first();

                if ($kelasItem) {
                    $kelasItem->update(['wali_kelas_id' => $waliId]);
                }
            }
        }
    }
}
