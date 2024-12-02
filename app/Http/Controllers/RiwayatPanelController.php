<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPanel;
use Illuminate\Http\Request;

class RiwayatPanelController extends Controller
{
    // Menampilkan semua riwayat Panel
    public function index()
    {
        $riwayatPanels = RiwayatPanel::with('panel')->get();
        return response()->json($riwayatPanels);
    }

    // Menampilkan riwayat Panel berdasarkan ID
    public function show($id)
    {
        $riwayatPanel = RiwayatPanel::with('panel')->find($id);

        if (!$riwayatPanel) {
            return response()->json(['message' => 'Riwayat Panel not found'], 404);
        }

        return response()->json($riwayatPanel);
    }

    // Get Riwayat Panel by panel_id
    public function getRiwayatByPanel($id_panel)
    {
        $riwayatPanel = RiwayatPanel::where('panel_id', $id_panel)->get();

        if ($riwayatPanel->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada pengaduan atau kerusakan tercatat untuk ID Panel ' . $id_panel
            ], 200);
        }

        return response()->json([
            'data' => $riwayatPanel
        ], 200);
    }

    // Membuat riwayat Panel baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lokasi' => 'nullable|string',
            'tanggal_masalah' => 'nullable|date',
            'jam_masalah' => 'nullable|string',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'jam_penyelesaian' => 'nullable|string',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'nomer_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        $riwayatPanel = RiwayatPanel::create($validated);

        return response()->json($riwayatPanel, 201);
    }

    // Mengupdate riwayat Panel
    public function update(Request $request, $id)
    {
        $riwayatPanel = RiwayatPanel::find($id);

        if (!$riwayatPanel) {
            return response()->json(['message' => 'Riwayat Panel not found'], 404);
        }

        $validated = $request->validate([
            'lokasi' => 'nullable|string',
            'tanggal_masalah' => 'nullable|date',
            'jam_masalah' => 'nullable|string',
            'keterangan_masalah' => 'nullable|string',
            'uraian_masalah' => 'nullable|string',
            'tanggal_penyelesaian' => 'nullable|date',
            'jam_penyelesaian' => 'nullable|string',
            'durasi_penyelesaian' => 'nullable|string',
            'penyelesaian_masalah' => 'nullable|string',
            'nomer_rujukan' => 'nullable|string',
            'status' => 'required|in:Pending,Selesai,Proses',
        ]);

        $riwayatPanel->update($validated);

        return response()->json($riwayatPanel);
    }

    // Menghapus riwayat Panel
    public function destroy($id)
    {
        $riwayatPanel = RiwayatPanel::find($id);

        if (!$riwayatPanel) {
            return response()->json(['message' => 'Riwayat Panel not found'], 404);
        }

        $riwayatPanel->delete();

        return response()->json(['message' => 'Riwayat Panel deleted successfully']);
    }
}
