<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdditionalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar 25 user baru dengan detail unik
        $newUsers = [
            ['name' => 'Mark Taylor', 'email' => 'mark.taylor@example.com', 'avatar' => 'MT', 'color' => '#f6ad55'],
            ['name' => 'Emily Chen', 'email' => 'emily.chen@example.com', 'avatar' => 'EC', 'color' => '#63b3ed'],
            ['name' => 'David Lee', 'email' => 'david.lee@example.com', 'avatar' => 'DL', 'color' => '#9f7aea'],
            ['name' => 'Olivia Garcia', 'email' => 'olivia.garcia@example.com', 'avatar' => 'OG', 'color' => '#f56565'],
            ['name' => 'James Rodriguez', 'email' => 'james.r@example.com', 'avatar' => 'JR', 'color' => '#4c51bf'],
            ['name' => 'Ava Martinez', 'email' => 'ava.martinez@example.com', 'avatar' => 'AM', 'color' => '#38b2ac'],
            ['name' => 'Ethan Hernandez', 'email' => 'ethan.h@example.com', 'avatar' => 'EH', 'color' => '#ecc94b'],
            ['name' => 'Mia Lopez', 'email' => 'mia.lopez@example.com', 'avatar' => 'ML', 'color' => '#e9d8fd'],
            ['name' => 'Noah Perez', 'email' => 'noah.perez@example.com', 'avatar' => 'NP', 'color' => '#feb2b2'],
            ['name' => 'Sophia Scott', 'email' => 'sophia.scott@example.com', 'avatar' => 'SS', 'color' => '#b2f5ea'],
            ['name' => 'Jacob King', 'email' => 'jacob.king@example.com', 'avatar' => 'JK', 'color' => '#c3dafe'],
            ['name' => 'Isabella Green', 'email' => 'isabella.g@example.com', 'avatar' => 'IG', 'color' => '#a0aec0'],
            ['name' => 'William Baker', 'email' => 'william.baker@example.com', 'avatar' => 'WB', 'color' => '#d53f8c'],
            ['name' => 'Charlotte Adams', 'email' => 'charlotte.a@example.com', 'avatar' => 'CA', 'color' => '#0bc5ea'],
            ['name' => 'Alexander White', 'email' => 'alexander.w@example.com', 'avatar' => 'AW', 'color' => '#319795'],
            ['name' => 'Amelia Hall', 'email' => 'amelia.hall@example.com', 'avatar' => 'AH', 'color' => '#d69e2e'],
            ['name' => 'Michael Clark', 'email' => 'michael.clark@example.com', 'avatar' => 'MC', 'color' => '#718096'],
            ['name' => 'Evelyn Young', 'email' => 'evelyn.y@example.com', 'avatar' => 'EY', 'color' => '#805ad5'],
            ['name' => 'Daniel Lewis', 'email' => 'daniel.lewis@example.com', 'avatar' => 'DL', 'color' => '#4a5568'],
            ['name' => 'Harper Hill', 'email' => 'harper.h@example.com', 'avatar' => 'HH', 'color' => '#f6e0b7'],
            ['name' => 'Logan Scott', 'email' => 'logan.scott@example.com', 'avatar' => 'LS', 'color' => '#3f51b5'],
            ['name' => 'Ella Miller', 'email' => 'ella.miller@example.com', 'avatar' => 'EM', 'color' => '#009688'],
            ['name' => 'Benjamin Turner', 'email' => 'benjamin.t@example.com', 'avatar' => 'BT', 'color' => '#ff5722'],
            ['name' => 'Scarlett Harris', 'email' => 'scarlett.h@example.com', 'avatar' => 'SH', 'color' => '#e91e63'],
            ['name' => 'Henry Walker', 'email' => 'henry.walker@example.com', 'avatar' => 'HW', 'color' => '#9c27b0'],
        ];

        foreach ($newUsers as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'avatar' => $user['avatar'],
                'color' => $user['color'],
                'role' => 'user', // Semua user baru ini memiliki role 'user'
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Password default: 'password'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
