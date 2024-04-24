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
    }
}
