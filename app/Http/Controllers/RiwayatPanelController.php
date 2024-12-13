<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPanel;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPengaduan;
use Illuminate\Http\Request;

class RiwayatPanelController extends Controller
{
    public function index($panel_id)
    {
        $dataPanel = DB::table('data_panels')->where('id_panel', $panel_id)->first();
        $riwayatPanels = RiwayatPanel::where('panel_id', $panel_id)->get();

        $pengaduanDetails = DetailPengaduan::with('pengaduan')
            ->where('panel_id', $panel_id)
            ->get();

        return response()->json([
            'no_app' => $dataPanel->no_app ?? 'Unknown',
            'riwayat_panels' => $riwayatPanels,
            'pengaduan_details' => $pengaduanDetails,
        ]);
    }

    /**
     * Menambahkan riwayat Panel baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'panel_id' => 'required|exists:data_panels,id_panel',
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

        $riwayatPanels = RiwayatPanel::create($validated);

        return response()->json([
            'message' => 'Riwayat Panel berhasil ditambahkan.',
            'riwayat_panel' => $riwayatPanels,
        ]);
    }

    /**
     * Memperbarui riwayat Panel tertentu.
     */
    public function update(Request $request, $id)
    {
        $riwayatPanels = RiwayatPanel::findOrFail($id);

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

        $riwayatPanels->update($validated);

        return response()->json([
            'message' => 'Riwayat Panel berhasil diperbarui.',
            'riwayat_panel' => $riwayatPanels,
        ]);
    }

    /**
     * Menghapus riwayat Panel tertentu.
     */
    public function destroy($id)
    {
        $riwayatPanels = RiwayatPanel::findOrFail($id);
        $riwayatPanels->delete();

        return response()->json(['message' => 'Riwayat Panel berhasil dihapus.']);
    }
}