<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            Kendaraan::factory(rand(1, 10))->create(['pelanggan_id' => $pelanggan->id]);
        }
    }
}
