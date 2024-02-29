<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Progres>
 */
class ProgresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'keterangan' => $this->faker->sentence(10),
            // 'status' => $this->faker->randomElement(['Selesai', 'Dalam Proses']),
            'status' => 'Dalam Proses',
        ];
    }
}
