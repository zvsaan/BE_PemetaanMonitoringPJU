<?php

namespace App\Exports;

use App\Models\DataPanel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\WithAutoFilter;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DataPanelExport implements FromCollection, WithHeadings, WithTitle, WithStrictNullComparison
{
    public function collection()
    {
        return DataPanel::select(
            'no_app', 'longitude', 'latitude', 'abd_no', 'no_pondasi_tiang',
            'line1_120w', 'line1_120w_2l', 'line1_90w', 'line1_60w', 
            'line2_120w', 'line2_120w_2l', 'line2_90w', 'line2_60w', 
            'jumlah_pju', 'total_daya_beban', 'daya_app', 'daya_terpakai', 
            'arus_beban', 'nama_jalan', 'desa_kel', 'kecamatan', 'idpel', 
            'no_kwh', 'no_kunci', 'magnetik_kontaktor', 'timer', 'mcb_kwh', 
            'terminal_block', 'rccb', 'pilot_lamp'
        )->get();
    }

    public function headings(): array
    {
        return [
            'No APP', 'Longitude', 'Latitude', 'ABD No', 'No Pondasi Tiang',
            'Line 1 (120W)', 'Line 1 (120W) 2L', 'Line 1 (90W)', 'Line 1 (60W)', 
            'Line 2 (120W)', 'Line 2 (120W) 2L', 'Line 2 (90W)', 'Line 2 (60W)', 
            'Jumlah PJU', 'Total Daya Beban (W)', 'Daya APP', 'Daya Terpakai (%)', 
            'Arus Beban (A)', 'Nama Jalan', 'Desa/Kel', 'Kecamatan', 'IDPEL', 'No KWH', 
            'No Kunci', 'Magnetik Kontaktor', 'Timer', 'MCB KWH', 'Terminal Block', 'RCCB', 'Pilot Lamp'
        ];
    }

    public function title(): string
    {
        return 'Data Panel';
    }
}
