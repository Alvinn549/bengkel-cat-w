<?php

namespace Database\Seeders;

use App\Models\Tipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipes = [
            ['nama_tipe' => 'Motor'],
            ['nama_tipe' => 'Mobil'],
            ['nama_tipe' => 'Bus'],
            ['nama_tipe' => 'Trailer'],
            ['nama_tipe' => 'Truk'],
            ['nama_tipe' => 'Pick Up'],
            ['nama_tipe' => 'Sedan'],
            ['nama_tipe' => 'SUV'],
            ['nama_tipe' => 'MPV'],
        ];

        Tipe::insert($tipes);
    }
}
