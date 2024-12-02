<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPJU;
use Illuminate\Http\Request;

class RiwayatPJUController extends Controller
{
    // Menampilkan semua riwayat PJU
    public function index()
    {
        $riwayatPjus = RiwayatPJU::with('pju')->get();
        return response()->json($riwayatPjus);
    }

    // Menampilkan riwayat PJU berdasarkan ID
    public function show($id)
    {
        $riwayatPju = RiwayatPJU::with('pju')->find($id);

        if (!$riwayatPju) {
            return response()->json(['message' => 'Riwayat PJU not found'], 404);
        }

        return response()->json($riwayatPju);
    }

    // Get Riwayat PJU by id_pju
    public function getRiwayatByPJU($id_pju)
    {
        $riwayatPJU = RiwayatPJU::where('pju_id', $id_pju)->get();

        if ($riwayatPJU->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada pengaduan atau kerusakan tercatat untuk ID PJU ' . $id_pju
            ], 200);
        }

        return response()->json([
            'data' => $riwayatPJU
        ], 200);
    }

    // Membuat riwayat PJU baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pju_id' => 'required|exists:data_pjus,id_pju',
            'lokasi' => 'nullable|string',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'nomer_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        $riwayatPju = RiwayatPJU::create($validated);

        return response()->json($riwayatPju, 201);
    }

    // Mengupdate riwayat PJU
    public function update(Request $request, $id)
    {
        $riwayatPju = RiwayatPJU::find($id);

        if (!$riwayatPju) {
            return response()->json(['message' => 'Riwayat PJU not found'], 404);
        }

        $validated = $request->validate([
            'lokasi' => 'nullable|string',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'nomer_rujukan' => 'nullable|string',
            'status' => 'required|in:Pending,Selesai,Proses',
        ]);

        $riwayatPju->update($validated);

        return response()->json($riwayatPju);
    }

    // Menghapus riwayat PJU
    public function destroy($id)
    {
        $riwayatPju = RiwayatPJU::find($id);

        if (!$riwayatPju) {
            return response()->json(['message' => 'Riwayat PJU not found'], 404);
        }

        $riwayatPju->delete();

        return response()->json(['message' => 'Riwayat PJU deleted successfully']);
    }
}
