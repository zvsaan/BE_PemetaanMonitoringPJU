<?php

namespace App\Imports;

use App\Models\RiwayatPJU;
use App\Models\DataPJU;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RiwayatPjuImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Cari pju_id berdasarkan no_tiang_baru
            $pju = DataPJU::where('no_tiang_baru', $row['no_tiang'])->first();

            if ($pju) {
                RiwayatPJU::create([
                    'pju_id' => $pju->id_pju,
                    'lokasi' => $row['lokasi'] ?? null,
                    'tanggal_masalah' => $row['tanggal_masalah'] ?? null,
                    'jam_masalah' => $row['jam_masalah'] ?? null,
                    'keterangan_masalah' => $row['keterangan_masalah'] ?? null,
                    'uraian_masalah' => $row['uraian_masalah'] ?? null,
                    'tanggal_penyelesaian' => $row['tanggal_penyelesaian'] ?? null,
                    'jam_penyelesaian' => $row['jam_penyelesaian'] ?? null,
                    'durasi_penyelesaian' => $row['durasi_penyelesaian'] ?? null,
                    'penyelesaian_masalah' => $row['penyelesaian_masalah'] ?? null,
                    'pencegahan' => $row['pencegahan'] ?? null,
                    'nomor_rujukan' => $row['nomor_rujukan'] ?? null,
                    'status' => $row['status'] ?? 'Pending',
                ]);
            }
        }
    }
}