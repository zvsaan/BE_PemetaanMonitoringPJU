<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat admin default
        User::create([
            'username' => 'adminuser',
            'name' => 'Admin 1',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'foto' => null,
        ]);

        User::create([
            'username' => 'adminuser2',
            'name' => 'Admin 2',
            'role' => 'dishub',
            'password' => Hash::make('password123'),
            'foto' => null,
        ]);
    }
}