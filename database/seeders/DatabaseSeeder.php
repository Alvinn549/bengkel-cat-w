<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Pekerja;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PelangganSeeder::class,
            KendaraanSeeder::class,
            PerbaikanSeeder::class
        ]);

        $admin = User::create([
            'role' => 'admin',
            'is_active' => true,
            'device_id' => null,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now()
        ]);

        Admin::create([
            'user_id' => $admin->id,
            'nama' => 'Admin',
            'no_telp' => '08123456789',
            'alamat' => 'Pacitan, Jatim',
            'jenis_k' => 'L',
            'foto' => null,
            'created_at' => now()
        ]);


        $pelanggan = User::create([
            'role' => 'pelanggan',
            'is_active' => true,
            'device_id' => null,
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now()
        ]);

        Pelanggan::create([
            'user_id' => $pelanggan->id,
            'nama' => 'Pelanggan',
            'no_telp' => '08123456789',
            'alamat' => 'Pacitan, Jatim',
            'jenis_k' => 'L',
            'foto' => null,
            'created_at' => now()
        ]);

        $pekerja = User::create([
            'role' => 'pekerja',
            'is_active' => true,
            'device_id' => null,
            'email' => 'pekerja@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
        ]);

        Pekerja::create([
            'user_id' => $pekerja->id,
            'nama' => 'Pekerja',
            'no_telp' => '08123456789',
            'alamat' => 'Pacitan, Jatim',
            'jenis_k' => 'L',
            'foto' => null,
            'created_at' => now()
        ]);
    }
}
