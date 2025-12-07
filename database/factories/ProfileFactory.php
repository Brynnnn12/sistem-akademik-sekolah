<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'jenis_kelamin' => $this->faker->randomElement(['laki-laki', 'perempuan']),
            'photo' => null,
            'is_active' => true,
        ];
    }
}
