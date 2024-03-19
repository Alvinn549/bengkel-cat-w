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
            'no_plat' => $this->faker->bothify('?? #### ??'),
            'merek_id' => rand(1, 9),
            'tipe_id' => rand(1, 9),
            'keterangan' => $this->faker->sentence(10),
        ];
    }
}
