<?php

namespace App\Exports;

use App\Models\Pengaduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengaduanExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data dari tabel pengaduan untuk diexport ke Excel.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data pengaduan beserta No Tiang melalui relasi detail_pengaduan -> pju
        $pengaduanData = Pengaduan::with('detailPengaduans.pju')->get();

        return $pengaduanData->map(function ($pengaduan) {
            // Ambil No Tiang pertama jika ada relasi ke PJU
            $noTiang = $pengaduan->detailPengaduans->first()->pju->no_tiang_baru ?? 'N/A';

            return [
                // 'ID Pengaduan' => $pengaduan->id_pengaduan,
                'Source' => 'Pengaduan',
                'Nomor Pengaduan' => $pengaduan->nomor_pengaduan,
                'Pelapor' => $pengaduan->pelapor,
                'Lokasi' => $pengaduan->lokasi,
                'No Tiang' => $noTiang,
                'Kondisi Masalah' => $pengaduan->kondisi_masalah,
                'Tanggal Pengaduan' => \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y'),
                'Jam Aduan' => $pengaduan->jam_aduan,
                'Keterangan Masalah' => $pengaduan->keterangan_masalah,
                'Uraian Masalah' => $pengaduan->uraian_masalah,
                'Tanggal Penyelesaian' => $pengaduan->tanggal_penyelesaian ? \Carbon\Carbon::parse($pengaduan->tanggal_penyelesaian)->format('d M Y') : '-',
                'Jam Penyelesaian' => $pengaduan->jam_penyelesaian ?? '-',
                'Durasi Penyelesaian (Jam)' => $pengaduan->durasi_penyelesaian ?? '-',
                'Penyelesaian Masalah' => $pengaduan->penyelesaian_masalah,
                'Pencegahan Masalah' => $pengaduan->pencegahan_masalah,
                'Pengelompokan Masalah' => $pengaduan->pengelompokan_masalah,
                'Status' => $pengaduan->status,
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
            'Source',
            'Nomor Pengaduan',
            'Pelapor',
            'Lokasi',
            'No Tiang',
            'Kondisi Masalah',
            'Tanggal Pengaduan',
            'Jam Aduan',
            'Keterangan Masalah',
            'Uraian Masalah',
            'Tanggal Penyelesaian',
            'Jam Penyelesaian',
            'Durasi Penyelesaian (Jam)',
            'Penyelesaian Masalah',
            'Pencegahan Masalah',
            'Pengelompokan Masalah',
            'Status',
        ];
    }
}
