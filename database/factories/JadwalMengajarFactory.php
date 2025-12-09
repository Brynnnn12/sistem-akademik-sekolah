<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalMengajar>
 */
class JadwalMengajarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamMulai = $this->faker->time('H:i');
        $jamSelesai = date('H:i', strtotime($jamMulai) + rand(3600, 7200)); // 1-2 jam setelah mulai

        return [
            'penugasan_mengajar_id' => \App\Models\PenugasanMengajar::factory(),
            'hari' => $this->faker->randomElement($hariList),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ];
    }
}
