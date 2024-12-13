<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPengaduanSeeder extends Seeder
{
    public function run()
    {
        $detailPengaduanData = [
            [
                'pengaduan_id' => 1,
                'panel_id' => NULL,
                'pju_id' => 2,
            ],
            [
                'pengaduan_id' => 2,
                'panel_id' => NULL,
                'pju_id' => 2,
            ],
            [
                'pengaduan_id' => 3,
                'panel_id' => NULL,
                'pju_id' => 2,
            ],
            [
                'pengaduan_id' => 4,
                'panel_id' => NULL,
                'pju_id' => 1,
            ],
        ];

        foreach ($detailPengaduanData as $data) {
            DB::table('detail_pengaduan')->insert($data);
        }
    }
}
