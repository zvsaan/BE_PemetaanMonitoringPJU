<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatPanelSpecificExport implements FromCollection, WithHeadings
{
    protected $panelId;

    public function __construct($panelId)
    {
        $this->panelId = $panelId;
    }

    public function collection()
    {
        // Query gabungan data RiwayatPanel dan Pengaduan
        $data = DB::select(
            "
            (
                SELECT 
                    'Riwayat' AS source,
                    dp.no_app AS no_app,
                    rp.lokasi AS lokasi,
                    DATE_FORMAT(rp.tanggal_masalah, '%d %M %Y') AS tanggal_masalah,
                    TIME_FORMAT(rp.jam_masalah, '%H:%i') AS jam_masalah,
                    rp.keterangan_masalah AS keterangan_masalah,
                    rp.uraian_masalah AS uraian_masalah,
                    DATE_FORMAT(rp.tanggal_penyelesaian, '%d %M %Y') AS tanggal_penyelesaian,
                    TIME_FORMAT(rp.jam_penyelesaian, '%H:%i') AS jam_penyelesaian,
                    rp.durasi_penyelesaian AS durasi_penyelesaian,
                    rp.penyelesaian_masalah AS penyelesaian_masalah,
                    rp.pencegahan AS pencegahan,
                    COALESCE(rp.nomor_rujukan, '-') AS nomor_rujukan,
                    rp.status AS status
                FROM riwayat_panels rp
                JOIN data_panels dp ON rp.panel_id = dp.id_panel
                WHERE rp.panel_id = ?
            )
            UNION ALL
            (
                SELECT 
                    'Pengaduan' AS source,
                    dp.no_app AS no_app,
                    p.lokasi AS lokasi,
                    DATE_FORMAT(p.tanggal_pengaduan, '%d %M %Y') AS tanggal_masalah,
                    TIME_FORMAT(p.jam_aduan, '%H:%i') AS jam_masalah,
                    p.keterangan_masalah AS keterangan_masalah,
                    p.uraian_masalah AS uraian_masalah,
                    DATE_FORMAT(p.tanggal_penyelesaian, '%d %M %Y') AS tanggal_penyelesaian,
                    TIME_FORMAT(p.jam_penyelesaian, '%H:%i') AS jam_penyelesaian,
                    p.durasi_penyelesaian AS durasi_penyelesaian,
                    p.penyelesaian_masalah AS penyelesaian_masalah,
                    p.pencegahan_masalah AS pencegahan,
                    '-' AS nomor_rujukan,
                    p.status AS status
                FROM detail_pengaduan dpd
                JOIN pengaduan p ON dpd.pengaduan_id = p.id_pengaduan
                JOIN data_panels dp ON dpd.panel_id = dp.id_panel
                WHERE dpd.panel_id = ?
            )
            ",
            [$this->panelId, $this->panelId]
        );

        // Mengonversi hasil query menjadi koleksi untuk Excel
        return collect($data);
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
