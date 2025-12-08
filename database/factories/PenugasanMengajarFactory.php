<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenugasanMengajar>
 */
class PenugasanMengajarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guru_id' => User::role('Guru')->inRandomOrder()->first()?->id ?? User::factory()->create()->assignRole('Guru')->id,
            'kelas_id' => Kelas::inRandomOrder()->first()?->id ?? Kelas::factory(),
            'mata_pelajaran_id' => MataPelajaran::inRandomOrder()->first()?->id ?? MataPelajaran::factory(),
            'tahun_ajaran_id' => TahunAjaran::inRandomOrder()->first()?->id ?? TahunAjaran::factory(),
        ];
    }

    /**
     * Indicate that the penugasan is for active tahun ajaran.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'tahun_ajaran_id' => TahunAjaran::aktif()->first()?->id ?? TahunAjaran::factory()->aktif(),
        ]);
    }
}
