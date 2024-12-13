<?php

namespace App\Http\Controllers;

use App\Models\DataKonstruksi;
use App\Models\DataPju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KonstruksiController extends Controller
{
    // Menampilkan semua data konstruksi
    public function index()
    {
        $dataKonstruksi = DataKonstruksi::with('pju:id_pju,no_tiang_baru')->get();

        // Tambahkan no_tiang_baru ke hasil
        $result = $dataKonstruksi->map(function ($data) {
            return [
                'id_konstruksi' => $data->id_konstruksi,
                'no_tiang_baru' => $data->pju->no_tiang_baru ?? null,
                'tanggal_penggalian' => $data->tanggal_penggalian,
                'tanggal_pengecoran' => $data->tanggal_pengecoran,
                'pemasangan_tiang' => $data->pemasangan_tiang,
                'grounding_finishing' => $data->grounding_finishing,
                'pemasangan_aksesories' => $data->pemasangan_aksesories,
                'pemasangan_mcb' => $data->pemasangan_mcb,
            ];
        });

        return response()->json($result, 200);
    }

    // Menambahkan data konstruksi baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_tiang_baru' => 'required|exists:data_pjus,no_tiang_baru',
            'tanggal_penggalian' => 'required|date',
            'tanggal_pengecoran' => 'required|date',
            'pemasangan_tiang' => 'required|date',
            'grounding_finishing' => 'required|date',
            'pemasangan_aksesories' => 'required|date',
            'pemasangan_mcb' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cari PJU berdasarkan no_tiang_baru
        $pju = DataPju::where('no_tiang_baru', $request->no_tiang_baru)->first();

        if (!$pju) {
            return response()->json(['message' => 'No Tiang Baru tidak ditemukan'], 404);
        }

        // Cek apakah sudah ada data konstruksi untuk pju_id ini
        $existingData = DataKonstruksi::where('pju_id', $pju->id_pju)->first();
        if ($existingData) {
            return response()->json([
                'message' => 'Data konstruksi untuk No Tiang Baru ini sudah ada.'
            ], 409); // HTTP 409 Conflict
        }

        // Tambahkan data konstruksi baru
        DataKonstruksi::create([
            'pju_id' => $pju->id_pju,
            'tanggal_penggalian' => $request->tanggal_penggalian,
            'tanggal_pengecoran' => $request->tanggal_pengecoran,
            'pemasangan_tiang' => $request->pemasangan_tiang,
            'grounding_finishing' => $request->grounding_finishing,
            'pemasangan_aksesories' => $request->pemasangan_aksesories,
            'pemasangan_mcb' => $request->pemasangan_mcb,
        ]);

        return response()->json(['message' => 'Data konstruksi berhasil ditambahkan'], 201);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no_tiang_baru' => 'required|exists:data_pjus,no_tiang_baru',
            'tanggal_penggalian' => 'required|date',
            'tanggal_pengecoran' => 'required|date',
            'pemasangan_tiang' => 'required|date',
            'grounding_finishing' => 'required|date',
            'pemasangan_aksesories' => 'required|date',
            'pemasangan_mcb' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = DataKonstruksi::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pju = DataPju::where('no_tiang_baru', $request->no_tiang_baru)->first();

        $data->update([
            'pju_id' => $pju->id_pju,
            'tanggal_penggalian' => $request->tanggal_penggalian,
            'tanggal_pengecoran' => $request->tanggal_pengecoran,
            'pemasangan_tiang' => $request->pemasangan_tiang,
            'grounding_finishing' => $request->grounding_finishing,
            'pemasangan_aksesories' => $request->pemasangan_aksesories,
            'pemasangan_mcb' => $request->pemasangan_mcb,
        ]);

        return response()->json(['message' => 'Data konstruksi berhasil diperbarui'], 200);
    }

    // Menghapus data konstruksi berdasarkan ID
    public function destroy($id)
    {
        $data = DataKonstruksi::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data konstruksi berhasil dihapus'], 200);
    }
}
