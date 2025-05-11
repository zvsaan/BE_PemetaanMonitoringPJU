<?php

namespace Database\Seeders;

use App\Models\NavbarItem;
use Illuminate\Database\Seeder;

class NavbarItemsSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        NavbarItem::truncate();

        // Data menu utama
        $home = NavbarItem::create([
            'title' => 'Beranda',
            'url' => '/',
            'type' => 'link',
            'order' => 1,
            'is_published' => true
        ]);

        $about = NavbarItem::create([
            'title' => 'Tentang Kami',
            'url' => '/tentangkami',
            'type' => 'dropdown',
            'order' => 2,
            'is_published' => true
        ]);

        // Submenu Tentang Kami
        NavbarItem::create([
            'title' => 'Sekilas Tentang Kami',
            'url' => '/tentangkami/sekilas',
            'type' => 'link',
            'parent_id' => $about->id,
            'order' => 1,
            'is_published' => true
        ]);

        NavbarItem::create([
            'title' => 'Sejarah Kami',
            'url' => '/tentangkami/sejarah',
            'type' => 'link',
            'parent_id' => $about->id,
            'order' => 2,
            'is_published' => true
        ]);

        NavbarItem::create([
            'title' => 'Area Operasi Kami',
            'url' => '/tentangkami/area-operasi',
            'type' => 'link',
            'parent_id' => $about->id,
            'order' => 3,
            'is_published' => true
        ]);

        NavbarItem::create([
            'title' => 'Layanan Kami',
            'url' => '/tentangkami/layanan',
            'type' => 'link',
            'parent_id' => $about->id,
            'order' => 4,
            'is_published' => true
        ]);

        NavbarItem::create([
            'title' => 'Team Kami',
            'url' => '/tentangkami/team',
            'type' => 'link',
            'parent_id' => $about->id,
            'order' => 5,
            'is_published' => true
        ]);

        // Menu lainnya
        NavbarItem::create([
            'title' => 'Media',
            'url' => '/media',
            'type' => 'link',
            'order' => 3,
            'is_published' => true
        ]);

        NavbarItem::create([
            'title' => 'Kontak',
            'url' => '/contact',
            'type' => 'link',
            'order' => 4,
            'is_published' => true
        ]);
    }
}