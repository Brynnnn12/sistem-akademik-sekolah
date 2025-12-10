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
        // Buat 20 guru menggunakan factory
        \App\Models\User::factory()->count(20)->create()->each(function ($user) {
            $user->assignRole('Guru');

            // Buat profile untuk guru
            \App\Models\Profile::factory()->create([
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        });
    }
}
