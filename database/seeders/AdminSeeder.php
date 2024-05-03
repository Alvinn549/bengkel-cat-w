<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'role' => 'admin',
            'device_id' => null,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
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

        // User::factory(20)->create([
        //     'role' => 'admin',
        // ])->each(function ($user) {
        //     Admin::factory()->create(['user_id' => $user->id]);
        // });
    }
}
