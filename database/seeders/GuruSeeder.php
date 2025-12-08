<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa guru contoh
        $gurus = [
            [
                'name' => 'Ahmad Susanto',
                'email' => 'ahmad.susanto@guru.sch.id',
                'nip' => '1987654321',
                'nama' => 'Ahmad Susanto, S.Pd.',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Pendidikan No. 1',
                'jenis_kelamin' => 'laki-laki',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@guru.sch.id',
                'nip' => '1987654322',
                'nama' => 'Siti Aminah, S.Pd.',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Pendidikan No. 2',
                'jenis_kelamin' => 'perempuan',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@guru.sch.id',
                'nip' => '1987654323',
                'nama' => 'Budi Santoso, S.Pd.',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Pendidikan No. 3',
                'jenis_kelamin' => 'laki-laki',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@guru.sch.id',
                'nip' => '1987654324',
                'nama' => 'Maya Sari, S.Pd.',
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Pendidikan No. 4',
                'jenis_kelamin' => 'perempuan',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@guru.sch.id',
                'nip' => '1987654325',
                'nama' => 'Rudi Hartono, S.Pd.',
                'no_hp' => '081234567895',
                'alamat' => 'Jl. Pendidikan No. 5',
                'jenis_kelamin' => 'laki-laki',
            ],
        ];

        foreach ($gurus as $guruData) {
            $user = User::create([
                'name' => $guruData['name'],
                'email' => $guruData['email'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('Guru');

            Profile::create([
                'user_id' => $user->id,
                'nip' => $guruData['nip'],
                'nama' => $guruData['nama'],
                'no_hp' => $guruData['no_hp'],
                'alamat' => $guruData['alamat'],
                'jenis_kelamin' => $guruData['jenis_kelamin'],
                'is_active' => true,
            ]);
        }
    }
}
