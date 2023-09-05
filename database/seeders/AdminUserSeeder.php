<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'email' => 'admin@mysite.com',
            'email_verified_at' => now(),
            'phone' => rand(1111111111,9999999999),
            'password' => 'password',
            'type' => 1,
            'remember_token' => Str::random(10),
        ]);
    }
}
