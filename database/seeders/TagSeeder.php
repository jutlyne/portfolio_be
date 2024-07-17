<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->truncate();

        DB::table('tags')->insert([
            ['name' => 'PHP'],
            ['name' => 'NodeJs'],
            ['name' => 'VueJs'],
            ['name' => 'AWS'],
            ['name' => 'CICD'],
            ['name' => 'Life'],
        ]);
    }
}
