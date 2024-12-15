<?php

namespace App\Exports;

use App\Models\RiwayatPanel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatPanelExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data riwayat PJU untuk diekspor ke Excel.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data Riwayat PJU dengan relasi ke PJU untuk mendapatkan No Tiang
        $riwayatData = RiwayatPanel::with('panel')->get();

        // Memformat data agar sesuai dengan kolom yang diinginkan
        return $riwayatData->map(function ($item) {
            return [
                // 'ID Riwayat' => $item->id_riwayat_pju,
                'Source' => 'Riwayat',
                'No App' => $item->panel->no_app ?? 'N/A',
                'Lokasi' => $item->lokasi,
                'Tanggal Masalah' => $item->tanggal_masalah ? \Carbon\Carbon::parse($item->tanggal_masalah)->format('d M Y') : '-',
                'Jam Masalah' => $item->jam_masalah ?? '-',
                'Keterangan Masalah' => $item->keterangan_masalah,
                'Uraian Masalah' => $item->uraian_masalah,
                'Tanggal Penyelesaian' => $item->tanggal_penyelesaian ? \Carbon\Carbon::parse($item->tanggal_penyelesaian)->format('d M Y') : '-',
                'Jam Penyelesaian' => $item->jam_penyelesaian ?? '-',
                'Durasi Penyelesaian (Jam)' => $item->durasi_penyelesaian ?? '-',
                'Penyelesaian Masalah' => $item->penyelesaian_masalah,
                'Pencegahan' => $item->pencegahan,
                'Nomor Rujukan' => $item->nomor_rujukan ?? '-',
                'Status' => $item->status,
            ];
        });
    }

    /**
     * Mengatur heading untuk kolom Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            // 'ID Riwayat',
            'Source',
            'No App', // Kolom No Tiang
            'Lokasi',
            'Tanggal Masalah',
            'Jam Masalah',
            'Keterangan Masalah',
            'Uraian Masalah',
            'Tanggal Penyelesaian',
            'Jam Penyelesaian',
            'Durasi Penyelesaian (Jam)',
            'Penyelesaian Masalah',
            'Pencegahan',
            'Nomor Rujukan',
            'Status',
        ];
    }
}