<?php

namespace App\Http\Controllers;

use App\Models\DataPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PanelsExport;

class PanelController extends Controller
{

    public function index()
    {
        $dataPanels = DataPanel::all();
        return response()->json($dataPanels);
    }

    public function dropdownPanels()
    {
        $panels = DataPanel::select('id_panel', 'no_app', 'abd_no')->get();

        $dropdownPanels = $panels->map(function ($panel) {
            return [
                'value' => $panel->id_panel,
                'label' => "Panel {$panel->id_panel} - No APP: {$panel->no_app} - ABD No: {$panel->abd_no}",
            ];
        });

        return response()->json($dropdownPanels);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lapisan' => 'nullable|string|max:255',
            'no_app' => 'required|string|max:255',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'abd_no' => 'nullable|string|max:255',
            'no_pondasi_tiang' => 'nullable|string|max:255',
            'line1_120w' => 'nullable|integer',
            'line1_120w_2l' => 'nullable|integer',
            'line1_90w' => 'nullable|integer',
            'line1_60w' => 'nullable|integer',
            'line2_120w' => 'nullable|integer',
            'line2_120w_2l' => 'nullable|integer',
            'line2_90w' => 'nullable|integer',
            'line2_60w' => 'nullable|integer',
            'jumlah_pju' => 'nullable|integer',
            'total_daya_beban' => 'nullable|integer',
            'daya_app' => 'nullable|integer',
            'daya_terpakai' => 'nullable|string|max:255',
            'arus_beban' => 'nullable|string|max:255',
            'nama_jalan' => 'nullable|string|max:255',
            'desa_kel' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'idpel' => 'nullable|string|max:255',
            'no_kwh' => 'nullable|string|max:255',
            'no_kunci' => 'nullable|string|max:255',
            'magnetik_kontaktor' => 'nullable|string|max:255',
            'timer' => 'nullable|string|max:255',
            'mcb_kwh' => 'nullable|string|max:255',
            'terminal_block' => 'nullable|string|max:255',
            'rccb' => 'nullable|string|max:255',
            'pilot_lamp' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataPanel = DataPanel::create($request->all());

        return response()->json($dataPanel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        return response()->json($dataPanel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'lapisan' => 'nullable|string|max:255',
            'no_app' => 'required|string|max:255',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'abd_no' => 'nullable|string|max:255',
            'no_pondasi_tiang' => 'nullable|string|max:255',
            'line1_120w' => 'nullable|integer',
            'line1_120w_2l' => 'nullable|integer',
            'line1_90w' => 'nullable|integer',
            'line1_60w' => 'nullable|integer',
            'line2_120w' => 'nullable|integer',
            'line2_120w_2l' => 'nullable|integer',
            'line2_90w' => 'nullable|integer',
            'line2_60w' => 'nullable|integer',
            'jumlah_pju' => 'nullable|integer',
            'total_daya_beban' => 'nullable|integer',
            'daya_app' => 'nullable|integer',
            'daya_terpakai' => 'nullable|string|max:255',
            'arus_beban' => 'nullable|string|max:255',
            'nama_jalan' => 'nullable|string|max:255',
            'desa_kel' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'idpel' => 'nullable|string|max:255',
            'no_kwh' => 'nullable|string|max:255',
            'no_kunci' => 'nullable|string|max:255',
            'magnetik_kontaktor' => 'nullable|string|max:255',
            'timer' => 'nullable|string|max:255',
            'mcb_kwh' => 'nullable|string|max:255',
            'terminal_block' => 'nullable|string|max:255',
            'rccb' => 'nullable|string|max:255',
            'pilot_lamp' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataPanel->update($request->all());

        return response()->json($dataPanel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        $dataPanel->delete();

        return response()->json(['message' => 'Data Panel deleted successfully']);
    }
}
