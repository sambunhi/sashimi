<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keywords')->insert([
            ['name' => 'Applied Materials'],
            ['name' => 'ASML'],
            ['name' => 'SUMCO'],
            ['name' => '烏克蘭'],
            ['name' => '歐盟'],
            ['name' => '防疫']
        ]);
    }
}
