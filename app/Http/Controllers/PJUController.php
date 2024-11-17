<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PJUController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        
        $pjus = DataPJU::with('panel')
            ->when($search, function ($query) use ($search) {
                $query->where('no_tiang_baru', 'like', "%$search%")
                    //   ->orWhere('kecamatan', 'like', "%$search%")
                    ->orWhere('no_app', 'like', "%$search%");
            })
            ->paginate($perPage);

        return response()->json($pjus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lapisan' => 'required|integer',
            'no_app' => 'required|integer',
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
            'no_app' => 'required|integer',
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
    public function updateRecommendations()
    {
        $pjus = DataPJU::all();

        foreach ($pjus as $pju) {
            if ($pju->tanggal_pemasangan_tiang && $pju->lifetime_tiang) {
                $usiaTiang = Carbon::parse($pju->tanggal_pemasangan_tiang)->diffInYears(Carbon::now());
                $pju->rekomendasi_tiang = $usiaTiang >= $pju->lifetime_tiang ? 'Perlu Diganti' : 'Baik';
            }

            if ($pju->tanggal_pemasangan_lampu && $pju->lifetime_lampu) {
                $usiaLampu = Carbon::parse($pju->tanggal_pemasangan_lampu)->diffInYears(Carbon::now());
                $pju->rekomendasi_lampu = $usiaLampu >= $pju->lifetime_lampu ? 'Perlu Diganti' : 'Baik';
            }

            $pju->save();
        }

        return response()->json(['message' => 'Rekomendasi diperbarui']);
    }
}