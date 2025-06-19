<?php

namespace App\Exports;

use App\Models\Pengaduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PengaduanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua data pengaduan dengan relasi detailPengaduans + pju + panel
        $pengaduanData = Pengaduan::with('detailPengaduans.pju', 'detailPengaduans.panel')->get();

        return $pengaduanData->map(function ($pengaduan) {
            $detail = $pengaduan->detailPengaduans->first();

            $noTiang = $detail->pju->id_pju ?? 'Tidak Ada PJU';
            $namaPanel = $detail->panel->id_panel ?? 'Tidak Ada Panel';

            return [
                'Nomor Pengaduan'           => $pengaduan->nomor_pengaduan,
                'Pelapor'                   => $pengaduan->pelapor,
                'Lokasi'                    => $pengaduan->lokasi,
                'No Tiang'                  => $noTiang,
                'Nama Panel'                => $namaPanel,
                'Kondisi Masalah'           => $pengaduan->kondisi_masalah,
                'Tanggal Pengaduan'         => Carbon::parse($pengaduan->tanggal_pengaduan)->format('d M Y'),
                'Jam Aduan'                 => $pengaduan->jam_aduan,
                'Keterangan Masalah'        => $pengaduan->keterangan_masalah,
                'Uraian Masalah'            => $pengaduan->uraian_masalah,
                'Tanggal Penyelesaian'      => $pengaduan->tanggal_penyelesaian ? Carbon::parse($pengaduan->tanggal_penyelesaian)->format('d M Y') : '-',
                'Jam Penyelesaian'          => $pengaduan->jam_penyelesaian ?? '-',
                'Durasi Penyelesaian (Jam)' => $pengaduan->durasi_penyelesaian ?? '-',
                'Penyelesaian Masalah'      => $pengaduan->penyelesaian_masalah,
                'Pencegahan Masalah'        => $pengaduan->pencegahan_masalah,
                'Pengelompokan Masalah'     => $pengaduan->pengelompokan_masalah,
                'Status'                    => $pengaduan->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nomor Pengaduan',
            'Pelapor',
            'Lokasi',
            'No Tiang',
            'Nama Panel',
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
