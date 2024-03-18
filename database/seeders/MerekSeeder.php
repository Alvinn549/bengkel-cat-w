<?php

namespace Database\Seeders;

use App\Models\Merek;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mereks = [
            ['nama_merek' => 'Honda'],
            ['nama_merek' => 'Yamaha'],
            ['nama_merek' => 'Suzuki'],
            ['nama_merek' => 'Kawasaki'],
            ['nama_merek' => 'BMW'],
            ['nama_merek' => 'Ducati'],
            ['nama_merek' => 'KTM'],
            ['nama_merek' => 'Triumph'],
            ['nama_merek' => 'Husqvarna'],
        ];

        Merek::insert($mereks);
    }
}
