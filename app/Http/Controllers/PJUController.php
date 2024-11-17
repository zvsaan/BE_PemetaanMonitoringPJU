<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PjusExport;

class PJUController extends Controller
{
    public function export()
    {
        return Excel::download(new PjusExport, 'DataPanel.xlsx');
    }

    public function index(Request $request)
    {
        $pjus = DataPJU::with('panel')->get();
        return response()->json($pjus);
    }

    public function getKecamatanList()
    {
        $kecamatan = DataPJU::select('kecamatan')->distinct()->orderBy('kecamatan')->get();
        return response()->json($kecamatan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lapisan' => 'required|integer',
            'no_tiang_lama' => 'nullable|integer',
            'no_tiang_baru' => 'nullable|integer',
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_tiang' => 'required|integer',
            'jenis_tiang' => 'required|string|max:255',
            'spesifikasi_tiang' => 'nullable|string|max:255',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string|max:255',
            'tanggal_pemasangan_tiang' => 'nullable|date',
            'tanggal_pemasangan_lampu' => 'nullable|date',
            'lifetime_tiang' => 'nullable|integer',
            'lifetime_lampu' => 'nullable|integer',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pju = DataPJU::create($request->all());

        return response()->json($pju, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pju = DataPJU::with('panel')->find($id);

        if (!$pju) {
            return response()->json(['message' => 'Data PJU not found'], 404);
        }

        return response()->json($pju);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pju = DataPJU::find($id);

        if (!$pju) {
            return response()->json(['message' => 'Data PJU not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lapisan' => 'required|integer',
            'no_tiang_lama' => 'nullable|integer',
            'no_tiang_baru' => 'nullable|integer',
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_tiang' => 'required|integer',
            'jenis_tiang' => 'required|string|max:255',
            'spesifikasi_tiang' => 'nullable|string|max:255',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string|max:255',
            'tanggal_pemasangan_tiang' => 'nullable|date',
            'tanggal_pemasangan_lampu' => 'nullable|date',
            'lifetime_tiang' => 'nullable|integer',
            'lifetime_lampu' => 'nullable|integer',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pju->update($request->all());

        return response()->json($pju);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pju = DataPJU::find($id);

        if (!$pju) {
            return response()->json(['message' => 'Data PJU not found'], 404);
        }

        $pju->delete();

        return response()->json(['message' => 'Data PJU deleted successfully']);
    }

    /**
     * Update recommendations for all PJU records.
     */
    public function updateRekomendasi(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'id_pju' => 'required|integer|exists:data_pjus,id_pju',
        ]);

        // Ambil data PJU berdasarkan ID
        $pju = DataPju::findOrFail($request->id_pju);

        // Update rekomendasi_tiang
        if ($pju->tanggal_pemasangan_tiang) {
            $pju->rekomendasi_tiang = $pju->tinggi_tiang >= 9
                ? Carbon::parse($pju->tanggal_pemasangan_tiang)->addYears(5)->toDateString()
                : Carbon::parse($pju->tanggal_pemasangan_tiang)->addYears(4)->toDateString();
        }

        // Update rekomendasi_lampu
        if ($pju->tanggal_pemasangan_lampu) {
            $pju->rekomendasi_lampu = match ($pju->daya_lampu) {
                120 => Carbon::parse($pju->tanggal_pemasangan_lampu)->addYears(3)->toDateString(),
                90 => Carbon::parse($pju->tanggal_pemasangan_lampu)->addYears(4)->toDateString(),
                60 => Carbon::parse($pju->tanggal_pemasangan_lampu)->addYears(5)->toDateString(),
                default => null,
            };
        }

        // Simpan data
        $pju->save();

        return response()->json([
            'message' => 'Rekomendasi berhasil diupdate',
            'data' => $pju,
        ]);
    }
}