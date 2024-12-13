<?php

namespace App\Exports;

use App\Models\DataKonstruksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKonstruksiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil data dari database dan format tanggal
        return DataKonstruksi::with('pju:id_pju,no_tiang_baru')->get()->map(function ($data) {
            return [
                'no_tiang_baru' => $data->pju->no_tiang_baru,
                'tanggal_penggalian' => $this->formatTanggal($data->tanggal_penggalian),
                'tanggal_pengecoran' => $this->formatTanggal($data->tanggal_pengecoran),
                'pemasangan_tiang' => $this->formatTanggal($data->pemasangan_tiang),
                'grounding_finishing' => $this->formatTanggal($data->grounding_finishing),
                'pemasangan_aksesories' => $this->formatTanggal($data->pemasangan_aksesories),
                'pemasangan_mcb' => $this->formatTanggal($data->pemasangan_mcb),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Tiang Baru',
            'Tanggal Penggalian',
            'Tanggal Pengecoran',
            'Pemasangan Tiang',
            'Grounding Finishing',
            'Pemasangan Aksesories',
            'Pemasangan MCB',
        ];
    }

    private function formatTanggal($date)
    {
        return \Carbon\Carbon::parse($date)->translatedFormat('d F Y'); 
    }
}
