<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPJU;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPengaduan;
use Illuminate\Http\Request;

class RiwayatPjuController extends Controller
{
    public function index($pju_id)
    {
        $dataPJU = DB::table('data_pjus')->where('id_pju', $pju_id)->first();
        $riwayatPjus = RiwayatPJU::where('pju_id', $pju_id)->get();

        $pengaduanDetails = DetailPengaduan::with('pengaduan')
            ->where('pju_id', $pju_id)
            ->get();

        return response()->json([
            'no_tiang_baru' => $dataPJU->no_tiang_baru ?? 'Unknown',
            'riwayat_pjus' => $riwayatPjus,
            'pengaduan_details' => $pengaduanDetails,
        ]);
    }

    /**
     * Menambahkan riwayat PJU baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pju_id' => 'required|exists:data_pjus,id_pju',
            'lokasi' => 'nullable|string',
            'tanggal_masalah' => 'nullable|date',
            'jam_masalah' => 'nullable',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'jam_penyelesaian' => 'nullable',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        $riwayatPju = RiwayatPJU::create($validated);

        return response()->json([
            'message' => 'Riwayat PJU berhasil ditambahkan.',
            'riwayat_pju' => $riwayatPju,
        ]);
    }

    /**
     * Memperbarui riwayat PJU tertentu.
     */
    public function update(Request $request, $id)
    {
        $riwayatPju = RiwayatPJU::findOrFail($id);

        $validated = $request->validate([
            'lokasi' => 'nullable|string',
            'tanggal_masalah' => 'nullable|date',
            'jam_masalah' => 'nullable',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'jam_penyelesaian' => 'nullable',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        $riwayatPju->update($validated);

        return response()->json([
            'message' => 'Riwayat PJU berhasil diperbarui.',
            'riwayat_pju' => $riwayatPju,
        ]);
    }

    /**
     * Menghapus riwayat PJU tertentu.
     */
    public function destroy($id)
    {
        $riwayatPju = RiwayatPJU::findOrFail($id);
        $riwayatPju->delete();

        return response()->json(['message' => 'Riwayat PJU berhasil dihapus.']);
    }
}