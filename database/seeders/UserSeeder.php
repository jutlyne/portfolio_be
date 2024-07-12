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
            'username' => 'jutlyne',
            'email' => 'vocaoky290999@gmail.com',
            'password' => Hash::make('Matkhau@123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('user_profiles')->where('user_id', 1)->delete();
        DB::table('user_profiles')->insert([
            'user_id' => 1,
            'fullname' => 'Võ Cao Kỳ',
            'birth_date' => '1999-11-03'
        ]);
    }
}
