<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllRiwayatPjuExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengambil data dari RiwayatPJU dan Pengaduan
        $riwayat = \App\Models\RiwayatPJU::with('pju')->get(); // Relasi untuk mendapatkan No Tiang
        $pengaduan = \App\Models\Pengaduan::with('detailPengaduans.pju')->get(); // Relasi ke PJU melalui DetailPengaduan

        // Format data Riwayat PJU
        $riwayatData = $riwayat->map(function ($item) {
            return [
                'source' => 'Riwayat',
                'no_tiang' => $item->pju->no_tiang_baru ?? 'N/A',
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

        // Format data Pengaduan
        $pengaduanData = $pengaduan->map(function ($item) {
            return [
                'source' => 'Pengaduan',
                'no_tiang' => $item->detailPengaduans->first()->pju->no_tiang_baru ?? 'N/A',
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
                'nomor_rujukan' => $item->nomor_rujukan ?? '-',
                'status' => $item->status,
            ];
        });

        // Gabungkan data
        return $riwayatData->merge($pengaduanData);
    }

    public function headings(): array
    {
        return [
            'Source',
            'No Tiang',
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
