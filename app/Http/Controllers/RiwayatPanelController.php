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

    public function getPanelStatus($panel_id)
    {
        $statuses = [];

        // Ambil semua status dari RiwayatPanel
        $riwayatStatuses = RiwayatPanel::where('panel_id', $panel_id)->pluck('status')->toArray();
        $statuses = array_merge($statuses, $riwayatStatuses);

        // Ambil semua status dari DetailPengaduan
        $pengaduanStatuses = DetailPengaduan::where('panel_id', $panel_id)
            ->with('pengaduan')
            ->get()
            ->pluck('pengaduan.status')
            ->toArray();
        $statuses = array_merge($statuses, $pengaduanStatuses);

        // Prioritaskan status
        if (in_array('Pending', $statuses)) {
            return 'Pending';
        } elseif (in_array('Proses', $statuses)) {
            return 'Proses';
        }

        // Default ke Selesai jika tidak ada status Pending atau Proses
        return 'Selesai';
    }

    public function getPanelsWithStatus()
    {
        $panels = DB::table('data_panels')->get();

        $panelsWithStatus = $panels->map(function ($panel) {
            $panel->status = $this->getPanelStatus($panel->id_panel);
            return $panel;
        });

        return response()->json($panelsWithStatus);
    }

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
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        // Validasi status pengaduan terkait panel
        $existingPengaduanStatuses = DetailPengaduan::where('panel_id', $validated['panel_id'])
            ->whereHas('pengaduan', function ($query) {
                $query->whereIn('status', ['Pending', 'Proses']);
            })
            ->exists();

        if ($existingPengaduanStatuses) {
            return response()->json([
                'message' => 'Tidak dapat menambahkan riwayat baru. Masih ada Pengaduan dengan status "Pending" atau "Proses".',
            ], 400);
        }

        // Hitung durasi penyelesaian (jika ada)
        $validated['durasi_penyelesaian'] = $this->calculateDuration(
            $validated['tanggal_masalah'] ?? null,
            $validated['jam_masalah'] ?? null,
            $validated['tanggal_penyelesaian'] ?? null,
            $validated['jam_penyelesaian'] ?? null
        );

        // Simpan riwayat panel
        $riwayatPanels = RiwayatPanel::create($validated);

        return response()->json([
            'message' => 'Riwayat Panel berhasil ditambahkan.',
            'riwayat_panel' => $riwayatPanels,
        ]);
    }

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
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        // Hitung durasi jika tanggal dan jam penyelesaian diubah
        $validated['durasi_penyelesaian'] = $this->calculateDuration(
            $validated['tanggal_masalah'] ?? $riwayatPanels->tanggal_masalah,
            $validated['jam_masalah'] ?? $riwayatPanels->jam_masalah,
            $validated['tanggal_penyelesaian'] ?? $riwayatPanels->tanggal_penyelesaian,
            $validated['jam_penyelesaian'] ?? $riwayatPanels->jam_penyelesaian
        );

        $riwayatPanels->update($validated);

        return response()->json([
            'message' => 'Riwayat Panel berhasil diperbarui.',
            'riwayat_panel' => $riwayatPanels,
        ]);
    }

    private function calculateDuration($startDate, $startTime, $endDate, $endTime)
    {
        if ($startDate && $startTime && $endDate && $endTime) {
            $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "$startDate $startTime");
            $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "$endDate $endTime");

            $diffInMinutes = $end->diffInMinutes($start);
            $hours = intdiv($diffInMinutes, 60);
            $minutes = $diffInMinutes % 60;

            return "$hours jam, $minutes menit";
        }

        return null;
    }

    public function destroy($id)
    {
        $riwayatPanels = RiwayatPanel::findOrFail($id);
        $riwayatPanels->delete();

        return response()->json(['message' => 'Riwayat Panel berhasil dihapus.']);
    }
}