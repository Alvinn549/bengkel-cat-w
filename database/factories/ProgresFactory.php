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
            'pekerja_id' => $this->faker->numberBetween(1, 5),
            'keterangan' => $this->faker->sentence(10),
            'is_selesai' => false,
            'created_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
        ];
    }
}
