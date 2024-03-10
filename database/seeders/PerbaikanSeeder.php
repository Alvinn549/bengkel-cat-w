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

        // $totalKendaraanCount = Kendaraan::count();

        // Kendaraan::chunk(100, function ($kendaraans) use ($totalKendaraanCount) {
        //     $selectedKendaraan = Kendaraan::inRandomOrder()->limit(intval($totalKendaraanCount * 0.5))->get();

        //     foreach ($kendaraans as $kendaraan) {
        //         if ($selectedKendaraan->contains('id', $kendaraan->id)) {
        //             Perbaikan::factory(rand(1, 15))->create(['kendaraan_id' => $kendaraan->id]);
        //         }
        //     }
        // });
    }
}
