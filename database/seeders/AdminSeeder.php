<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat admin default
        Admin::create([
            'username' => 'adminuser', // Username untuk login
            'name' => 'Admin Name', // Nama admin
            'password' => Hash::make('password123'), // Hash password
        ]);
    }
}