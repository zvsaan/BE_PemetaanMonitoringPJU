<?php

namespace App\Exports;

use App\Models\DataPJU;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PjusExport implements FromCollection, WithHeadings
{
    /**
     * Fetch the data for export.
     */
    public function collection()
    {
        // Ambil data dengan join ke panel jika dibutuhkan
        return DataPJU::select([
            'lapisan',
            'panel_id',
            'no_tiang_lama',
            'no_tiang_baru',
            'nama_jalan',
            'kecamatan',
            'tinggi_tiang',
            'jenis_tiang',
            'daya_lampu',
            'status_jalan',
            'longitude',
            'latitude',
        ])->get();
    }

    /**
     * Set the headings for the export file.
     */
    public function headings(): array
    {
        return [
            'Lapisan',
            'ID Panel',
            'No. Tiang Lama',
            'No. Tiang Baru',
            'Nama Jalan',
            'Kecamatan',
            'Tinggi Tiang (m)',
            'Jenis Tiang',
            'Daya Lampu (W)',
            'Status Jalan',
            'Longitude',
            'Latitude',
        ];
    }
}