<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder untuk admin
        $this->call(UserSeeder::class);
        // $this->call(RiwayatPjuSeeder::class);
        // $this->call(RiwayatPanelSeeder::class);
        // $this->call(PengaduanSeeder::class);
        // $this->call(DetailPengaduanSeeder::class);
    }
}