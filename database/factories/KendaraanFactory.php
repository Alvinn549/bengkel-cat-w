<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kendaraan>
 */
class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'pelanggan_id' => null,
            'no_plat' => $this->faker->numerify('###-###'),
            'merek' => $this->faker->randomElement(['Avanza', 'Kijang', 'Honda', 'Suzuki']),
            'tipe' => $this->faker->randomElement(['Mobil', 'Motor', 'Truk']),
            'foto' => $this->faker->imageUrl(),
            'keterangan' => $this->faker->sentences(),
        ];
    }
}
