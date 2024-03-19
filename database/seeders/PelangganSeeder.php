<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pelanggan = User::create([
            'role' => 'pelanggan',
            'device_id' => null,
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
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

        User::factory(20)->create([
            'role' => 'pelanggan',
        ])->each(function ($user) {
            Pelanggan::factory()->create(['user_id' => $user->id]);
        });
    }
}
