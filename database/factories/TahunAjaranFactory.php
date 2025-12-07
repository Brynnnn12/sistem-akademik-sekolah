<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->randomElement([
                '2023/2024',
                '2024/2025',
                '2025/2026',
                '2026/2027',
            ]),
            'semester' => fake()->randomElement(['ganjil', 'genap']),
            'aktif' => fake()->boolean(30), // 30% chance of being active
        ];
    }

    /**
     * Indicate that the tahun ajaran is active.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'aktif' => true,
        ]);
    }

    /**
     * Indicate that the tahun ajaran is inactive.
     */
    public function tidakAktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'aktif' => false,
        ]);
    }

    /**
     * Create a tahun ajaran with ganjil semester.
     */
    public function ganjil(): static
    {
        return $this->state(fn(array $attributes) => [
            'semester' => 'ganjil',
        ]);
    }

    /**
     * Create a tahun ajaran with genap semester.
     */
    public function genap(): static
    {
        return $this->state(fn(array $attributes) => [
            'semester' => 'genap',
        ]);
    }
}
