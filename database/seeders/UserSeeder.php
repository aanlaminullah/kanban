<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'avatar' => 'JD', 'color' => '#4299e1'],
            ['name' => 'Alice Smith', 'email' => 'alice@example.com', 'avatar' => 'AS', 'color' => '#48bb78'],
            ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'avatar' => 'BJ', 'color' => '#ed8936'],
            ['name' => 'Sarah Wilson', 'email' => 'sarah@example.com', 'avatar' => 'SW', 'color' => '#e53e3e'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'avatar' => $user['avatar'],
                'color' => $user['color'],
                'role' => 'user',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
