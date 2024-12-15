<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use App\Models\DataPanel;
use App\Models\RiwayatPanel;
use App\Models\RiwayatPJU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataPJUExport;

class PJUController extends Controller
{
    public function exportDataPJU()
    {
        return Excel::download(new DataPJUExport, 'data_pju.xlsx');
    }

    public function index(Request $request)
    {
        $pjus = DataPJU::with('panel')->get();
        return response()->json($pjus);
    }

    public function filterDataByPanel(Request $request)
    {
        $selectedPanel = $request->query('panel_id');
        
        if ($selectedPanel) {
            $pjus = DataPJU::where('panel_id', $selectedPanel)->get();
        } else {
            $pjus = DataPJU::all();
        }

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
            'lapisan' => 'nullable|integer',
            'no_tiang_baru' => 'required|unique:data_pjus,no_tiang_baru',
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_tiang' => 'required|integer',
            'jenis_tiang' => 'required|string|max:255',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string|max:255',
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
            'lapisan' => 'nullable|integer',
            'no_tiang_baru' => 'required|integer',
            'nama_jalan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'tinggi_tiang' => 'required|integer',
            'jenis_tiang' => 'required|string|max:255',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string|max:255',
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
}