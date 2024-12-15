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
                'tanggal_masalah' => '2024-12-01',
                'jam_masalah' => '08:00:00',
                'keterangan_masalah' => 'Panel mati total',
                'uraian_masalah' => 'Panel tidak berfungsi sejak semalam',
                'tanggal_penyelesaian' => '2024-12-02',
                'jam_penyelesaian' => '14:00:00',
                'durasi_penyelesaian' => '360',
                'penyelesaian_masalah' => 'Diganti panel baru',
                'pencegahan' => 'Lakukan pengecekan rutin',
                'nomor_rujukan' => 'RJN001',
                'status' => 'Selesai',
            ],
            [
                'panel_id' => 2,
                'lokasi' => 'Jalan Mawar',
                'tanggal_masalah' => '2024-12-02',
                'jam_masalah' => '10:30:00',
                'keterangan_masalah' => 'Panel terbakar',
                'uraian_masalah' => 'Panel rusak akibat korsleting',
                'tanggal_penyelesaian' => null,
                'jam_penyelesaian' => null,
                'durasi_penyelesaian' => null,
                'penyelesaian_masalah' => null,
                'pencegahan' => 'Gunakan isolasi kabel berkualitas tinggi',
                'nomor_rujukan' => 'RJN002',
                'status' => 'Pending',
            ],
            [
                'panel_id' => 3,
                'lokasi' => 'Jalan Anggrek',
                'tanggal_masalah' => '2024-12-03',
                'jam_masalah' => '15:45:00',
                'keterangan_masalah' => 'Panel konslet',
                'uraian_masalah' => 'Panel tidak stabil menyala',
                'tanggal_penyelesaian' => '2024-12-04',
                'jam_penyelesaian' => '18:30:00',
                'durasi_penyelesaian' => '285',
                'penyelesaian_masalah' => 'Kabel diganti',
                'pencegahan' => 'Periksa instalasi listrik secara berkala',
                'nomor_rujukan' => 'RJN003',
                'status' => 'Selesai',
            ],
            [
                'panel_id' => 4,
                'lokasi' => 'Jalan Cempaka',
                'tanggal_masalah' => '2024-12-04',
                'jam_masalah' => '11:20:00',
                'keterangan_masalah' => 'Panel tidak menyala',
                'uraian_masalah' => 'Panel mati tanpa sebab jelas',
                'tanggal_penyelesaian' => null,
                'jam_penyelesaian' => null,
                'durasi_penyelesaian' => null,
                'penyelesaian_masalah' => null,
                'pencegahan' => 'Gunakan panel berkualitas tinggi',
                'nomor_rujukan' => 'RJN004',
                'status' => 'Proses',
            ],
            [
                'panel_id' => 5,
                'lokasi' => 'Jalan Dahlia',
                'tanggal_masalah' => '2024-12-05',
                'jam_masalah' => '09:00:00',
                'keterangan_masalah' => 'Panel berkedip',
                'uraian_masalah' => 'Panel berkedip tidak stabil',
                'tanggal_penyelesaian' => '2024-12-06',
                'jam_penyelesaian' => '12:00:00',
                'durasi_penyelesaian' => '180',
                'penyelesaian_masalah' => 'Konektor diganti',
                'pencegahan' => 'Pastikan kabel terpasang dengan benar',
                'nomor_rujukan' => 'RJN005',
                'status' => 'Selesai',
            ],
        ];

        DB::table('riwayat_panels')->insert($riwayatPanelData);
    }
}