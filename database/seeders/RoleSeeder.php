<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            /**
             * membuat role Admin, Guru, dan KepalaSekolah
             */
            $roles = ['Admin', 'Guru', 'KepalaSekolah'];

            foreach ($roles as $role) {
                \Spatie\Permission\Models\Role::create(['name' => $role]);
            }

            /**
             * membuat user admin dengan profile
             */
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('Admin');

            Profile::create([
                'user_id' => $admin->id,
                'nip' => '000000000',
                'nama' => 'Admin Contoh',
                'no_hp' => '081000000000',
                'alamat' => 'Alamat Admin',
                'jenis_kelamin' => 'laki-laki',
                'is_active' => true,
            ]);

            /**
             * membuat user guru dengan profile
             */
            $guru = User::create([
                'name' => 'Guru',
                'email' => 'guru@example.com',
                'password' => bcrypt('password'),
            ]);
            $guru->assignRole('Guru');

            Profile::create([
                'user_id' => $guru->id,
                'nip' => '123456789',
                'nama' => 'Guru Contoh',
                'no_hp' => '081234567890',
                'alamat' => 'Alamat Guru',
                'jenis_kelamin' => 'laki-laki',
                'is_active' => true,
            ]);

            /**
             * membuat user kepala sekolah dengan profile
             */
            $kepsek = User::create([
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@example.com',
                'password' => bcrypt('password'),
            ]);
            $kepsek->assignRole('KepalaSekolah');

            Profile::create([
                'user_id' => $kepsek->id,
                'nip' => '987654321',
                'nama' => 'Kepala Sekolah Contoh',
                'no_hp' => '081987654321',
                'alamat' => 'Alamat Kepsek',
                'jenis_kelamin' => 'laki-laki',
                'is_active' => true,
            ]);
        });
    }
}
