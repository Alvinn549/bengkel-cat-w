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
        $users = User::where('role', 'pelanggan')->get();

        foreach ($users as $user) {
            Pelanggan::factory()->create(['user_id' => $user->id]);
        }
    }
}
