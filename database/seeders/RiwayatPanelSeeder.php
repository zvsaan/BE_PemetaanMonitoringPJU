<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatPanelSeeder extends Seeder
{
    public function run()
    {
        $riwayatPanelData = [
            [
                'panel_id' => 1,
                'lokasi' => 'Jalan Melati',
                'tanggal_masalah' => '2024-12-05',
                'jam_masalah' => '20:00:00',
                'keterangan_masalah' => 'Panel berkedip',
                'uraian_masalah' => 'Panel tidak stabil menyala',
                'tanggal_penyelesaian' => '2024-12-06',
                'jam_penyelesaian' => '10:00:00',
                'durasi_penyelesaian' => '180',
                'penyelesaian_masalah' => 'Kabel diganti',
                'pencegahan' => 'Periksa soket kabel',
                'nomor_rujukan' => 'RJN001',
                'status' => 'Selesai',
            ],
            [
                'panel_id' => 2,
                'lokasi' => 'Jalan Mawar',
                'tanggal_masalah' => '2024-12-04',
                'jam_masalah' => '19:00:00',
                'keterangan_masalah' => 'Panel mati sebagian',
                'uraian_masalah' => 'Hanya 2 dari 4 Panel menyala',
                'tanggal_penyelesaian' => '2024-12-05',
                'jam_penyelesaian' => '13:00:00',
                'durasi_penyelesaian' => '240',
                'penyelesaian_masalah' => 'Panel diganti',
                'pencegahan' => 'Periksa intensitas arus',
                'nomor_rujukan' => 'RJN002',
                'status' => 'Selesai',
            ],
            [
                'panel_id' => 2,
                'lokasi' => 'Jalan Mawar2',
                'tanggal_masalah' => '2024-12-04',
                'jam_masalah' => '19:00:00',
                'keterangan_masalah' => 'Panel mati sebagian',
                'uraian_masalah' => 'Hanya 2 dari 4 Panel menyala',
                'tanggal_penyelesaian' => '2024-12-05',
                'jam_penyelesaian' => '13:00:00',
                'durasi_penyelesaian' => '240',
                'penyelesaian_masalah' => 'Panel diganti',
                'pencegahan' => 'Periksa intensitas arus',
                'nomor_rujukan' => 'RJN002',
                'status' => 'Selesai',
            ],
            // Tambahkan 8 data lainnya sesuai kebutuhan.
        ];

        DB::table('riwayat_panels')->insert($riwayatPanelData);
    }
}