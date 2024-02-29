<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perbaikan>
 */
class PerbaikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->words(3, true),
            'keterangan' => $this->faker->sentence(10),
            'biaya' => $this->faker->numerify('#######'),
            'durasi' => $this->faker->word(),
            'status' => $this->faker->randomElement(['Selesai', 'Dalam Proses', 'Ditunda', 'Dibatalkan', 'Tidak Dapat Diperbaiki']),
        ];
    }
}
