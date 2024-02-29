<?php

namespace Database\Seeders;

use App\Models\Perbaikan;
use App\Models\Progres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Perbaikan::chunk(100, function ($perbaikans) {
            foreach ($perbaikans as $perbaikan) {
                Progres::factory(rand(1, 15))->create(['perbaikan_id' => $perbaikan->id]);
            }
        });

        // $totalPerbaikanCount = Perbaikan::count();

        // Perbaikan::chunk(100, function ($perbaikans) use ($totalPerbaikanCount) {
        //     $selectedPerbaikans = Perbaikan::inRandomOrder()->limit(intval($totalPerbaikanCount * 0.5))->get();

        //     foreach ($perbaikans as $perbaikan) {
        //         if ($selectedPerbaikans->contains('id', $perbaikan->id)) {
        //             Progres::factory(rand(1, 15))->create(['perbaikan_id' => $perbaikan->id]);
        //         }
        //     }
        // });
    }
}
