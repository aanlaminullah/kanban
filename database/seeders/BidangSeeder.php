<?php
// database/seeders/BidangSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bidangs')->insert([
            [
                'name' => 'Aptika',
                'slug' => 'aptika',
                'color' => '#2f855a',
                'description' => 'Aplikasi dan Informatika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarkom',
                'slug' => 'sarkom',
                'color' => '#2b6cb0',
                'description' => 'Sarana Komunikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sekretariat',
                'slug' => 'sekretariat',
                'color' => '#b0a92b',
                'description' => 'Sekretariat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
