<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'Vo Cao Ky',
            'username' => 'jutlyne',
            'email' => 'vocaoky290999@gmail.com',
            'password' => Hash::make('Matkhau@123'),
            'email_verified_at' => now(),
        ]);
    }
}
