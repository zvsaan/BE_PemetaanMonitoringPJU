<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use Illuminate\Http\Request;

class PJUController extends Controller
{
    // Display all data
    public function index()
    {
        return DataPJU::all();
    }

    // Show a specific data record
    public function show($id)
    {
        return DataPJU::findOrFail($id);
    }

    // Store a new record
    public function store(Request $request)
{
    // Ambil semua data dari request tanpa validasi
    $dataPju = DataPJU::create($request->all());

    // Mengembalikan respons JSON dengan data yang baru disimpan dan status 201 (created)
    return response()->json($dataPju, 201);
}

    // Update an existing record
    public function update(Request $request, $id)
    {
        $dataPju = DataPJU::findOrFail($id);

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
            'daya_lampu' => 'required|integer|min:1',
            'status_jalan' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        $dataPju->update($validatedData);

        return response()->json($dataPju, 200);
    }

    // Delete a record
    public function destroy($id)
    {
        $dataPju = DataPJU::findOrFail($id);
        $dataPju->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
}