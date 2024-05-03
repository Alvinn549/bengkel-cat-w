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

        $deskripsi = 'Selamat datang di Bengkel Cat Wijayanto! Kami adalah bengkel yang berdedikasi untuk memberikan layanan perbaikan cat body kendaraan dengan kualitas terbaik. Sebagai salah satu bengkel terpercaya di wilayah kami, kami telah mendedikasikan diri untuk memberikan solusi terbaik bagi kendaraan Anda. Dengan pengalaman dan keahlian yang kami miliki, kami siap untuk menangani berbagai macam perbaikan cat, mulai dari penyok kecil hingga kerusakan yang lebih kompleks. Keunggulan kami terletak pada komitmen kami untuk memberikan hasil akhir yang memuaskan bagi setiap pelanggan. Dengan Bengkel Cat Wijayanto, Anda dapat memiliki keyakinan bahwa kendaraan Anda akan diperbaiki dengan profesionalisme dan perhatian yang maksimal.';
        $map = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.3156183415567!2d111.1135611!3d-8.170923599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e796164c4ede193%3A0x41d54b74544750be!2sBengkel%20Cat%20Wijayanto!5e0!3m2!1sen!2sid!4v1711349011265!5m2!1sen!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

        Settings::create([
            'master_nama' => 'Bengkel CAT Wijayanto',
            'deskripsi' => $deskripsi,
            'alamat' => 'Jl. AR Hakim No.25, Krajan IV, Semanten, Kec. Pacitan, Kabupaten Pacitan, Jawa Timur 63518',
            'map_google' => $map,
            'jam_operasional' => '07:30 to 16:00',
            'telepon' => '081234567890',
            'email' => 'bengkel-cat-w@bengkel-cat-w.com',
            'facebook' => 'https://facebook.com/bengkel-cat-w',
            'instagram' => 'https://instagram.com/bengkel-cat-w',
            'whatsapp' => 'https://wa.me/6281234567890',
        ]);
    }
}
