<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'no_telp' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'jenis_k' => $this->faker->randomElement(['L', 'P']),
        ];
    }
}
