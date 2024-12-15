<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPengaduanSeeder extends Seeder
{
    public function run()
    {
        $detailPengaduanData = [
            ['pengaduan_id' => 1, 'panel_id' => null, 'pju_id' => 1],
            ['pengaduan_id' => 2, 'panel_id' => null, 'pju_id' => 2],
            ['pengaduan_id' => 3, 'panel_id' => 1, 'pju_id' => null],
            ['pengaduan_id' => 4, 'panel_id' => null, 'pju_id' => 3],
            ['pengaduan_id' => 5, 'panel_id' => null, 'pju_id' => 4],
            ['pengaduan_id' => 6, 'panel_id' => 2, 'pju_id' => null],
            ['pengaduan_id' => 7, 'panel_id' => null, 'pju_id' => 5],
            ['pengaduan_id' => 8, 'panel_id' => null, 'pju_id' => 6],
            ['pengaduan_id' => 9, 'panel_id' => null, 'pju_id' => 7],
            ['pengaduan_id' => 10, 'panel_id' => 3, 'pju_id' => null],
        ];

        DB::table('detail_pengaduan')->insert($detailPengaduanData);
    }
}