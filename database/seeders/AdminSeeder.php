<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Ivan Bullo',
            'email' => 'bullo@javescooperative.com',
            'role' => 'admin',
            'password' => Hash::make('Bulloivan#14'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
