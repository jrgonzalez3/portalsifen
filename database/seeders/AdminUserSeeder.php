<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin SIFEN',
            'email' => env('AUTO_LOGIN_EMAIL', 'admin@sifen.local'),
            'password' => bcrypt(env('AUTO_LOGIN_PASSWORD', 'admin123')),
            'email_verified_at' => now(),
        ]);
    }
}
