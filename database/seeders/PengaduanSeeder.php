<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaduanSeeder extends Seeder
{
    public function run()
    {
        $pengaduanData = [
            [
                'nomor_pengaduan' => 'PND001',
                'pelapor' => 'John Doe',
                'kondisi_masalah' => 'Tiang',
                'lokasi' => 'Lokasi1',
                'tanggal_pengaduan' => '2024-12-01',
                'jam_aduan' => '08:30:00',
                'keterangan_masalah' => 'Lampu mati total',
                'uraian_masalah' => 'Lampu tidak menyala sejak malam kemarin',
                'tanggal_penyelesaian' => '2024-12-03',
                'jam_penyelesaian' => '11:00:00',
                'durasi_penyelesaian' => '150',
                'penyelesaian_masalah' => 'Diganti lampu baru',
                'pencegahan_masalah' => 'Periksa kabel secara berkala',
                'pengelompokan_masalah' => 'Internal',
                'status' => 'Selesai',
            ],
            [
                'nomor_pengaduan' => 'PND002',
                'pelapor' => 'Jane Smith',
                'kondisi_masalah' => 'Tiang',
                'lokasi' => 'Lokasi2',
                'tanggal_pengaduan' => '2024-12-02',
                'jam_aduan' => '10:00:00',
                'keterangan_masalah' => 'Tiang terbakar',
                'uraian_masalah' => 'Tiang rusak akibat korsleting listrik',
                'tanggal_penyelesaian' => '2024-12-04',
                'jam_penyelesaian' => '14:00:00',
                'durasi_penyelesaian' => '240',
                'penyelesaian_masalah' => 'Tiang diganti baru',
                'pencegahan_masalah' => 'Periksa Tiang sebelum hujan',
                'pengelompokan_masalah' => 'Eksternal',
                'status' => 'Selesai',
            ],
            [
                'nomor_pengaduan' => 'PND003',
                'pelapor' => 'Jane Smith',
                'kondisi_masalah' => 'Tiang',
                'lokasi' => 'Lokasi3',
                'tanggal_pengaduan' => '2024-12-02',
                'jam_aduan' => '10:00:00',
                'keterangan_masalah' => 'Tiang terbakar',
                'uraian_masalah' => 'Tiang rusak akibat korsleting listrik',
                'tanggal_penyelesaian' => '2024-12-04',
                'jam_penyelesaian' => '14:00:00',
                'durasi_penyelesaian' => '240',
                'penyelesaian_masalah' => 'Tiang diganti baru',
                'pencegahan_masalah' => 'Periksa Tiang sebelum hujan',
                'pengelompokan_masalah' => 'Eksternal',
                'status' => 'Selesai',
            ],
            [
                'nomor_pengaduan' => 'PND004',
                'pelapor' => 'User3',
                'kondisi_masalah' => 'Tiang',
                'lokasi' => 'Lokasi4',
                'tanggal_pengaduan' => '2024-12-02',
                'jam_aduan' => '10:00:00',
                'keterangan_masalah' => 'Tiang terbakar',
                'uraian_masalah' => 'Tiang rusak akibat korsleting listrik',
                'tanggal_penyelesaian' => '2024-12-04',
                'jam_penyelesaian' => '14:00:00',
                'durasi_penyelesaian' => '240',
                'penyelesaian_masalah' => 'Tiang diganti baru',
                'pencegahan_masalah' => 'Periksa Tiang sebelum hujan',
                'pengelompokan_masalah' => 'Eksternal',
                'status' => 'Selesai',
            ],
        ];

        DB::table('pengaduan')->insert($pengaduanData);
    }
}
