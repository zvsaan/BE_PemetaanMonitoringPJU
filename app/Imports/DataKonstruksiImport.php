<?php

namespace App\Imports;

use App\Models\DataKonstruksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataKonstruksiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Ambil pju_id berdasarkan no_tiang_baru
            $pjuId = $this->getPjuId($row['no_tiang_baru']);

            // Jika pju_id tidak ditemukan, lewati baris ini dan log peringatan
            if (!$pjuId) {
                \Log::warning("Baris dilewati: no_tiang_baru tidak ditemukan - " . $row['no_tiang_baru']);
                continue;
            }

            // Buat data baru di tabel data_konstruksis
            DataKonstruksi::create([
                'pju_id' => $pjuId,
                'tanggal_penggalian' => $this->convertExcelDate($row['tanggal_penggalian']),
                'tanggal_pengecoran' => $this->convertExcelDate($row['tanggal_pengecoran']),
                'pemasangan_tiang' => $this->convertExcelDate($row['pemasangan_tiang']),
                'grounding_finishing' => $this->convertExcelDate($row['pasang_grounding_finishing_pondasi_tiang']),
                'pemasangan_aksesories' => $this->convertExcelDate($row['pemasangan_aksesories_dan_penarikan_kabel_udara']),
                'pemasangan_mcb' => $this->convertExcelDate($row['pemasangan_mcb']),
            ]);
        }
    }

    /**
     * Konversi Excel serial date ke format Y-m-d
     */
    private function convertExcelDate($value)
    {
        try {
            // Jika nilai adalah angka (serial date Excel), konversi ke format tanggal
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // Jika bukan angka, langsung kembalikan nilai
            return $value;
        } catch (\Exception $e) {
            \Log::error("Error konversi tanggal: " . $value . " - " . $e->getMessage());
            return null; // Atau gunakan default seperti '1970-01-01'
        }
    }

    /**
     * Ambil pju_id berdasarkan no_tiang_baru
     */
    private function getPjuId($noTiangBaru)
    {
        $pju = \App\Models\DataPJU::where('no_tiang_baru', $noTiangBaru)->first();
        if (!$pju) {
            \Log::warning("No tiang baru tidak ditemukan: " . $noTiangBaru);
        }
        return $pju ? $pju->id_pju : null;
    }
}