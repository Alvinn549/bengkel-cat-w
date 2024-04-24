<?php

namespace Database\Seeders;

use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pekerja = User::create([
            'role' => 'pekerja',
            'device_id' => null,
            'email' => 'pekerja@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
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

        User::factory(5)->create([
            'role' => 'admin',
        ])->each(function ($user) {
            Pekerja::factory()->create(['user_id' => $user->id]);
        });
    }
}
