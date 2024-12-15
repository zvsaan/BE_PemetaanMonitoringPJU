<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatPjuSeeder extends Seeder
{
    public function run()
    {
        $riwayatPjuData = [
            [
                'pju_id' => 1,
                'lokasi' => 'Jalan Melati',
                'tanggal_masalah' => '2024-12-01',
                'jam_masalah' => '18:00:00',
                'keterangan_masalah' => 'Lampu mati total',
                'uraian_masalah' => 'Lampu tidak menyala sejak sore',
                'tanggal_penyelesaian' => '2024-12-02',
                'jam_penyelesaian' => '10:00:00',
                'durasi_penyelesaian' => '960',
                'penyelesaian_masalah' => 'Diganti lampu baru',
                'pencegahan' => 'Gunakan lampu berkualitas tinggi',
                'nomor_rujukan' => 'RJN001',
                'status' => 'Selesai',
            ],
            [
                'pju_id' => 2,
                'lokasi' => 'Jalan Mawar',
                'tanggal_masalah' => '2024-12-02',
                'jam_masalah' => '20:00:00',
                'keterangan_masalah' => 'Lampu redup',
                'uraian_masalah' => 'Lampu tidak menyala terang seperti biasanya',
                'tanggal_penyelesaian' => null,
                'jam_penyelesaian' => null,
                'durasi_penyelesaian' => null,
                'penyelesaian_masalah' => null,
                'pencegahan' => 'Periksa voltase listrik secara berkala',
                'nomor_rujukan' => 'RJN002',
                'status' => 'Pending',
            ],
            [
                'pju_id' => 3,
                'lokasi' => 'Jalan Anggrek',
                'tanggal_masalah' => '2024-12-03',
                'jam_masalah' => '21:30:00',
                'keterangan_masalah' => 'Lampu berkedip',
                'uraian_masalah' => 'Lampu menyala dan mati secara bergantian',
                'tanggal_penyelesaian' => '2024-12-04',
                'jam_penyelesaian' => '12:00:00',
                'durasi_penyelesaian' => '750',
                'penyelesaian_masalah' => 'Kabel diganti',
                'pencegahan' => 'Pastikan kabel terpasang dengan benar',
                'nomor_rujukan' => 'RJN003',
                'status' => 'Selesai',
            ],
            [
                'pju_id' => 4,
                'lokasi' => 'Jalan Dahlia',
                'tanggal_masalah' => '2024-12-04',
                'jam_masalah' => '19:00:00',
                'keterangan_masalah' => 'Lampu tidak menyala',
                'uraian_masalah' => 'Lampu mati tanpa sebab jelas',
                'tanggal_penyelesaian' => null,
                'jam_penyelesaian' => null,
                'durasi_penyelesaian' => null,
                'penyelesaian_masalah' => null,
                'pencegahan' => 'Periksa instalasi listrik secara berkala',
                'nomor_rujukan' => 'RJN004',
                'status' => 'Proses',
            ],
            [
                'pju_id' => 5,
                'lokasi' => 'Jalan Cempaka',
                'tanggal_masalah' => '2024-12-05',
                'jam_masalah' => '17:45:00',
                'keterangan_masalah' => 'Lampu terbakar',
                'uraian_masalah' => 'Lampu rusak akibat korsleting',
                'tanggal_penyelesaian' => '2024-12-06',
                'jam_penyelesaian' => '11:00:00',
                'durasi_penyelesaian' => '915',
                'penyelesaian_masalah' => 'Lampu diganti baru',
                'pencegahan' => 'Gunakan pelindung korsleting',
                'nomor_rujukan' => 'RJN005',
                'status' => 'Selesai',
            ],
        ];

        DB::table('riwayat_pjus')->insert($riwayatPjuData);
    }
}