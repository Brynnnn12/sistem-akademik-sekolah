<?php

namespace Database\Factories;

use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kehadiran>
 */
class KehadiranFactory extends Factory
{
    protected $model = Kehadiran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 80% Hadir, 10% Sakit, 5% Izin, 5% Alpha (realistic distribution)
        $statusWeighted = $this->faker->randomElement([
            ...array_fill(0, 80, 'H'),
            ...array_fill(0, 10, 'S'),
            ...array_fill(0, 5, 'I'),
            ...array_fill(0, 5, 'A'),
        ]);

        return [
            'siswa_id' => Siswa::inRandomOrder()->first()?->id ?? Siswa::factory(),
            'kelas_id' => Kelas::inRandomOrder()->first()?->id ?? Kelas::factory(),
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $statusWeighted,
            'catatan' => $this->getCatatan($statusWeighted),
        ];
    }

    /**
     * Get catatan based on status
     */
    private function getCatatan(string $status): ?string
    {
        return match ($status) {
            'H' => null,
            'S' => $this->faker->randomElement([
                'Demam',
                'Flu',
                'Sakit perut',
                'Batuk pilek',
                'Sakit kepala',
                null,
            ]),
            'I' => $this->faker->randomElement([
                'Keperluan keluarga',
                'Acara keluarga',
                'Ke dokter',
                null,
            ]),
            'A' => null,
            default => null,
        };
    }

    /**
     * Indicate kehadiran with Hadir status
     */
    public function hadir(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'H',
            'catatan' => null,
        ]);
    }

    /**
     * Indicate kehadiran with Sakit status
     */
    public function sakit(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'S',
            'catatan' => $this->faker->randomElement(['Demam', 'Flu', 'Sakit perut']),
        ]);
    }

    /**
     * Indicate kehadiran with Izin status
     */
    public function izin(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'I',
            'catatan' => $this->faker->randomElement(['Keperluan keluarga', 'Acara keluarga']),
        ]);
    }

    /**
     * Indicate kehadiran with Alpha status
     */
    public function alpha(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'A',
            'catatan' => null,
        ]);
    }
}
