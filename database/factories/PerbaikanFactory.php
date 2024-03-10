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
        $today = today()->format('d-m-Y');

        return [
            'kode_unik' => $this->faker->bothify('?????-#####'),
            'nama' => $this->faker->words(3, true),
            'keterangan' => $this->faker->sentence(10),
            'durasi' => $today . ' to ' . $this->faker->dateTimeBetween($today, '+30 days')->format('d-m-Y'),
            'status' => $this->faker->randomElement(['Dalam Proses', 'Ditunda', 'Dibatalkan', 'Tidak Dapat Diperbaiki']),
        ];
    }
}
