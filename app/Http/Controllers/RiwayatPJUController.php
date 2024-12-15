<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPJU;
use App\Models\DataPJU;
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

    public function getPjusWithStatus(Request $request)
    {
        $kecamatan = $request->query('kecamatan');

        if ($kecamatan) {
            $pjus = DataPJU::where('kecamatan', $kecamatan)->get()->map(function ($pju) {
                $statuses = [];

                // Ambil status dari RiwayatPJU
                $riwayatStatuses = RiwayatPJU::where('pju_id', $pju->id_pju)->pluck('status')->toArray();
                $statuses = array_merge($statuses, $riwayatStatuses);

                // Ambil status dari DetailPengaduan
                $pengaduanStatuses = DetailPengaduan::where('pju_id', $pju->id_pju)
                    ->with('pengaduan')
                    ->get()
                    ->pluck('pengaduan.status')
                    ->toArray();
                $statuses = array_merge($statuses, $pengaduanStatuses);

                // Prioritaskan status
                if (in_array('Pending', $statuses)) {
                    $pju->status = 'Pending';
                } elseif (in_array('Proses', $statuses)) {
                    $pju->status = 'Proses';
                } else {
                    $pju->status = 'Selesai';
                }

                return $pju;
            });
        } else {
            $pjus = collect();
        }

        return response()->json([
            'data_count' => $pjus->count(),
            'data' => $pjus,
        ]);
    }

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
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        $pjuId = $validated['pju_id'];

        // Validasi status dari RiwayatPJU
        $existingRiwayatStatuses = RiwayatPJU::where('pju_id', $pjuId)
            ->whereIn('status', ['Pending', 'Proses'])
            ->exists();

        if ($existingRiwayatStatuses) {
            return response()->json([
                'message' => 'Tidak dapat menambahkan riwayat baru. Masih ada Riwayat PJU dengan status "Pending" atau "Proses".',
            ], 400);
        }

        // Validasi status dari Pengaduan
        $existingPengaduanStatuses = DetailPengaduan::where('pju_id', $pjuId)
            ->whereHas('pengaduan', function ($query) {
                $query->whereIn('status', ['Pending', 'Proses']);
            })
            ->exists();

        if ($existingPengaduanStatuses) {
            return response()->json([
                'message' => 'Tidak dapat menambahkan riwayat baru. Masih ada Pengaduan dengan status "Pending" atau "Proses".',
            ], 400);
        }

        // Hitung durasi penyelesaian
        $validated['durasi_penyelesaian'] = $this->calculateDuration(
            $validated['tanggal_masalah'] ?? null,
            $validated['jam_masalah'] ?? null,
            $validated['tanggal_penyelesaian'] ?? null,
            $validated['jam_penyelesaian'] ?? null
        );

        // Tambahkan Riwayat PJU baru
        $riwayatPju = RiwayatPJU::create($validated);

        return response()->json([
            'message' => 'Riwayat PJU berhasil ditambahkan.',
            'riwayat_pju' => $riwayatPju,
        ]);
    }

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
            'penyelesaian_masalah' => 'nullable|string',
            'pencegahan' => 'nullable|string',
            'nomor_rujukan' => 'nullable|string',
            'status' => 'nullable|in:Pending,Selesai,Proses',
        ]);

        // Hitung durasi penyelesaian
        $validated['durasi_penyelesaian'] = $this->calculateDuration(
            $validated['tanggal_masalah'] ?? $riwayatPju->tanggal_masalah,
            $validated['jam_masalah'] ?? $riwayatPju->jam_masalah,
            $validated['tanggal_penyelesaian'] ?? $riwayatPju->tanggal_penyelesaian,
            $validated['jam_penyelesaian'] ?? $riwayatPju->jam_penyelesaian
        );

        // Update data Riwayat PJU
        $riwayatPju->update($validated);

        return response()->json([
            'message' => 'Riwayat PJU berhasil diperbarui.',
            'riwayat_pju' => $riwayatPju,
        ]);
    }

    /**
     * Menghitung durasi penyelesaian dalam format "X jam, Y menit".
     */
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