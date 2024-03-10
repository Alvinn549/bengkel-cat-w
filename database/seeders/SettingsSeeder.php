<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $deskripsi = '<p>' . implode('</p><p>', $faker->sentences(10)) . '</p>';

        Settings::create([
            'master_nama' => 'Bengkel CAT Wijayanto',
            'deskripsi' => $deskripsi,
            'alamat' => 'Jl. Mawar No. 1, Jakarta',
            'jam_operasional' => '08.00 to 17.00',
            'telepon' => '081234567890',
            'email' => 'bengkel-cat-w@bengkel-cat-w.com',
            'facebook' => 'https://facebook.com/bengkel-cat-w',
            'instagram' => 'https://instagram.com/bengkel-cat-w',
            'whatsapp' => 'https://wa.me/6281234567890',
        ]);
    }
}
