<?php

namespace App\Http\Controllers;

use App\Models\DataPanel;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    // Mendapatkan semua data panel
    public function index()
    {
        return response()->json(DataPanel::all(), 200);
    }

    // Menyimpan data panel baru
    public function store(Request $request)
    {
        // $request->validate([
        //     'lapisan' => 'required|string',
        //     'no_app' => 'required|string',
        //     'longitude' => 'required|numeric',
        //     'latitude' => 'required|numeric',
        //     // 'abd_no' => 'required|string',
        //     // 'no_pondasi_tiang' => 'required|string',
        // ]);

        $dataPanel = DataPanel::create($request->all());

        return response()->json($dataPanel, 201);
    }

    // Mendapatkan data panel berdasarkan ID
    public function show($id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        return response()->json($dataPanel, 200);
    }

    // Memperbarui data panel
    public function update(Request $request, $id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        $dataPanel->update($request->all());

        return response()->json($dataPanel, 200);
    }

    // Menghapus data panel
    public function destroy($id)
    {
        $dataPanel = DataPanel::find($id);

        if (!$dataPanel) {
            return response()->json(['message' => 'Data Panel not found'], 404);
        }

        $dataPanel->delete();

        return response()->json(['message' => 'Data Panel deleted successfully'], 200);
    }
}