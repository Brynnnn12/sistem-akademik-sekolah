<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisKelamin = $this->faker->randomElement(['L', 'P']);

        return [
            'nis' => $this->faker->unique()->numerify('##########'), // 10 digit NIS
            'nisn' => $this->faker->unique()->numerify('##########'), // 10 digit NISN
            'nama' => $jenisKelamin === 'L'
                ? $this->faker->name('male')
                : $this->faker->name('female'),
            'jenis_kelamin' => $jenisKelamin,
            'tanggal_lahir' => $this->faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
            'alamat' => $this->faker->address(),
        ];
    }
}
