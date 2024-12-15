<?php

namespace App\Imports;

use App\Models\RiwayatPanel;
use App\Models\DataPanel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RiwayatPanelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Cari panel_id berdasarkan no_tiang_baru
            $panel = DataPanel::where('no_app', $row['no_app'])->first();

            if ($panel) {
                RiwayatPanel::create([
                    // 'panel_id' => $panel->id_panel,
                    'panel_id' => $panel->id_panel,
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