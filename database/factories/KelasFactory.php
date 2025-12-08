<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkat = $this->faker->numberBetween(1, 6);
        $huruf = $this->faker->randomElement(['A', 'B', 'C']);

        // Ensure unique nama kelas
        do {
            $nama = $tingkat . $huruf;
        } while (Kelas::where('nama', $nama)->exists());

        return [
            'nama' => $nama,
            'tingkat_kelas' => $tingkat,
            'wali_kelas_id' => User::role('Guru')->inRandomOrder()->first()?->id ?? User::factory()->create()->assignRole('Guru')->id,
        ];
    }

    /**
     * Indicate that the kelas is for a specific tingkat.
     */
    public function tingkat(int $tingkat): static
    {
        return $this->state(fn(array $attributes) => [
            'tingkat_kelas' => $tingkat,
            'nama' => $tingkat . $this->faker->randomElement(['A', 'B', 'C']),
        ]);
    }

    /**
     * Indicate that the kelas has a specific wali kelas.
     */
    public function waliKelas(User $waliKelas): static
    {
        return $this->state(fn(array $attributes) => [
            'wali_kelas_id' => $waliKelas->id,
        ]);
    }
}
