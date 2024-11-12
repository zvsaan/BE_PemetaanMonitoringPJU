<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use Illuminate\Http\Request;

class PJUController extends Controller
{
    // Display all data
    public function index()
    {
        return DataPju::all();
    }

    // Show a specific data record
    public function show($id)
    {
        return DataPju::findOrFail($id);
    }

    // Store a new record
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lapisan' => 'required|string',
            'no_app' => 'required|string',
            'no_tiang_lama' => 'required|string',
            'no_tiang_baru' => 'required|string',
            'nama_jalan' => 'required|string',
            'kecamatan' => 'required|string',
            'tinggi_tiang' => 'required|numeric',
            'jenis_tiang' => 'required|string',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        return DataPju::create($validatedData);
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        $dataPju = DataPju::findOrFail($id);

        $validatedData = $request->validate([
            'panel_id' => 'required|exists:data_panels,id_panel',
            'lapisan' => 'required|string',
            'no_app' => 'required|string',
            'no_tiang_lama' => 'required|string',
            'no_tiang_baru' => 'required|string',
            'nama_jalan' => 'required|string',
            'kecamatan' => 'required|string',
            'tinggi_tiang' => 'required|numeric',
            'jenis_tiang' => 'required|string',
            'daya_lampu' => 'required|integer',
            'status_jalan' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        $dataPju->update($validatedData);

        return $dataPju;
    }

    // Delete a record
    public function destroy($id)
    {
        $dataPju = DataPju::findOrFail($id);
        $dataPju->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
