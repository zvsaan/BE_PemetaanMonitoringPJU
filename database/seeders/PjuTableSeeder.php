<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PjuTableSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/public/data1.csv'); // Path file CSV
        $data = array_map('str_getcsv', file($filePath));

        // Hilangkan header dari data
        $header = array_shift($data);

        foreach ($data as $row) {
            // Pengecekan jumlah kolom (ada 12 kolom berdasarkan data yang Anda berikan)
            if (count($row) < 12) {
                continue; // Skip baris jika kolom tidak lengkap
            }

            DB::table('pjus')->insert([
                'lapisan' => $row[0],
                'no_app' => $row[1],
                'no_tiang_lama' => $row[2],
                'no_tiang_baru' => $row[3],
                'nama_jalan' => $row[4],
                'kecamatan' => $row[5],
                'tinggi_tiang_m' => $row[6],
                'jenis_tiang' => $row[7],
                'daya_lampu_w' => $row[8],
                'status_jalan' => $row[9],
                'longitude' => $row[10],
                'latitude' => $row[11],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
