<?php

namespace App\Exports;

use App\Models\DataPJU;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataPJUExport implements FromCollection, WithHeadings, WithTitle
{

    public function collection()
    {
        return DataPJU::all([
            'panel_id', 'no_tiang_baru', 
            'nama_jalan', 'kecamatan', 'tinggi_tiang', 'jenis_tiang', 'daya_lampu', 
            'status_jalan', 'longitude', 'latitude'
        ]);
    }

    public function headings(): array
    {
        return [
            'No APP (Panel ID)', 'No Tiang Baru', 
            'Nama Jalan', 'Kecamatan', 'Tinggi Tiang', 'Jenis Tiang', 'Daya Lampu', 
            'Status Jalan', 'Longitude', 'Latitude'
        ];
    }

    public function title(): string
    {
        return 'Data PJU'; 
    }
}