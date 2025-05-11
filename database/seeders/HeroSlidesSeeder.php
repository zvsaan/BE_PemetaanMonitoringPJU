<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlidesSeeder extends Seeder
{
    public function run()
    {
        HeroSlide::truncate();

        $slides = [
            [
                'title' => 'Selamat Datang di PT Tri Tunggal Madiun Terang',
                'description' => 'Penyedia layanan penerangan jalan umum terpercaya di Kabupaten Madiun.',
                'image_path' => 'hero_slides/hero1.jpeg',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Solusi Penerangan Jalan Umum Terpadu',
                'description' => 'Kami menghadirkan teknologi modern untuk efisiensi dan keberlanjutan dalam penerangan jalan.',
                'image_path' => 'hero_slides/hero2.jpg',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Komitmen pada Pelayanan Berkualitas',
                'description' => 'Melayani masyarakat dengan pengelolaan penerangan jalan umum yang profesional dan responsif.',
                'image_path' => 'hero_slides/hero3.jpeg',
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Dukung Infrastruktur Ramah Lingkungan',
                'description' => 'Kami berperan aktif dalam pembangunan penerangan jalan yang hemat energi dan berkelanjutan.',
                'image_path' => 'hero_slides/hero4.jpeg',
                'order' => 4,
                'is_active' => true
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}