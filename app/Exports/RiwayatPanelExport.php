<?php

namespace App\Exports;

use App\Models\RiwayatPanel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPanelExport implements FromCollection, WithHeadings, WithMapping
{
    protected $riwayatData;

    // Konstruktor untuk menerima data riwayat panel yang sudah difilter
    public function __construct($riwayatData)
    {
        $this->riwayatData = $riwayatData;
    }

    // Fungsi untuk mengambil data dari koleksi
    public function collection()
    {
        return $this->riwayatData;
    }

    // Menentukan judul kolom di Excel
    public function headings(): array
    {
        return [
            'Lokasi', 'Keterangan Masalah', 'Uraian Masalah', 
            'Tanggal Masalah', 'Jam Masalah', 'Tanggal Penyelesaian',
            'Jam Penyelesaian', 'Durasi Penyelesaian', 'Penyelesaian Masalah',
            'Nomer Rujukan', 'Status'
        ];
    }

    // Menentukan bagaimana setiap baris data akan di-mapping
    public function map($riwayat): array
    {
        return [
            // $riwayat->id_riwayat_panel,
            $riwayat->lokasi,
            $riwayat->keterangan_masalah,
            $riwayat->uraian_masalah,
            $riwayat->tanggal_masalah,
            $riwayat->jam_masalah,
            $riwayat->tanggal_penyelesaian,
            $riwayat->jam_penyelesaian,
            $riwayat->durasi_penyelesaian,
            $riwayat->penyelesaian_masalah,
            $riwayat->nomer_rujukan,
            $riwayat->status,
        ];
    }
}
