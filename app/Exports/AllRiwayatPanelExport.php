<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllRiwayatPanelExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Data dari tabel riwayat_panels
        $riwayat = \App\Models\RiwayatPanel::with('panel')
            ->get()
            ->map(function ($item) {
                return [
                    'source' => 'Riwayat',
                    'no_app' => $item->panel->no_app ?? 'N/A',
                    'lokasi' => $item->lokasi,
                    'tanggal_masalah' => $item->tanggal_masalah,
                    'jam_masalah' => $item->jam_masalah,
                    'keterangan_masalah' => $item->keterangan_masalah,
                    'uraian_masalah' => $item->uraian_masalah,
                    'tanggal_penyelesaian' => $item->tanggal_penyelesaian,
                    'jam_penyelesaian' => $item->jam_penyelesaian,
                    'durasi_penyelesaian' => $item->durasi_penyelesaian,
                    'penyelesaian_masalah' => $item->penyelesaian_masalah,
                    'pencegahan' => $item->pencegahan,
                    'nomor_rujukan' => $item->nomor_rujukan ?? '-',
                    'status' => $item->status,
                ];
            });

        // Data dari tabel pengaduan yang terkait dengan panel
        $pengaduan = \App\Models\Pengaduan::whereHas('detailPengaduans', function ($query) {
            $query->whereNotNull('panel_id'); // Hanya detail pengaduan yang terkait panel
        })
        ->with('detailPengaduans.panel')
        ->get()
        ->map(function ($item) {
            return [
                'source' => 'Pengaduan',
                'no_app' => $item->detailPengaduans->first()->panel->no_app ?? 'N/A',
                'lokasi' => $item->lokasi,
                'tanggal_masalah' => $item->tanggal_pengaduan,
                'jam_masalah' => $item->jam_aduan,
                'keterangan_masalah' => $item->keterangan_masalah,
                'uraian_masalah' => $item->uraian_masalah,
                'tanggal_penyelesaian' => $item->tanggal_penyelesaian,
                'jam_penyelesaian' => $item->jam_penyelesaian,
                'durasi_penyelesaian' => $item->durasi_penyelesaian,
                'penyelesaian_masalah' => $item->penyelesaian_masalah,
                'pencegahan' => $item->pencegahan_masalah,
                'nomor_rujukan' => $item->nomor_pengaduan ?? '-',
                'status' => $item->status,
            ];
        });

        return $riwayat->merge($pengaduan);
    }

    public function headings(): array
    {
        return [
            'Source',
            'No App',
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
