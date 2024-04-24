<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Perbaikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kendaraan::chunk(50, function ($kendaraans) {
            foreach ($kendaraans as $kendaraan) {
                Perbaikan::factory(rand(1, 5))->create(['kendaraan_id' => $kendaraan->id]);
            }
        });
    }
}
