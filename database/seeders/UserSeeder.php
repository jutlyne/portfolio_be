<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        User::create([
            'name' => 'Vo Cao Ky',
            'username' => 'jutlyne',
            'email' => 'vocaoky290999@gmail.com',
            'password' => 'Kypro@2609',
            'email_verified_at' => now(),
        ]);
    }
}
