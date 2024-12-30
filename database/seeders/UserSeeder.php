<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Core Master',
            'email' => 'master@example.com',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Core Player',
            'email' => 'player@example.com',
            'password' => Hash::make('password'),
            'role' => 'player',
        ]);
    }
}
