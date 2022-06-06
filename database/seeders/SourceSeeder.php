<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sources')->insert([
            ['id' => 1, 'name' => 'Google', 'url' => 'https://www.google.com/search?q='],
            ['id' => 2, 'name' => 'Udn', 'url' => 'https://theme.udn.com/rss/news/1004/6772/6775?ch=theme'],
            ['id' => 3, 'name' => '報導者', 'url' => 'https://public.twreporter.org/rss/twreporter-rss.xml'],
            ['id' => 4, 'name' => '關鍵評論網', 'url' => 'http://feeds.feedburner.com/TheNewsLens?format=xml']
        ]);
    }
}
