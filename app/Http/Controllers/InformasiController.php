<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $informasis = Informasi::all();
        return response()->json([
            'status' => 'success',
            'data' => $informasis,
        ], 200);
    }

    public function show($id)
    {
        $informasi = Informasi::find($id);
        if ($informasi) {
            return response()->json([
                'status' => 'success',
                'data' => $informasi,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Informasi not found',
        ], 404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|string',
            'tanggal_publikasi' => 'required|date',
        ]);

        $informasi = Informasi::create($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $informasi,
            'message' => 'Informasi created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $informasi = Informasi::find($id);
        if ($informasi) {
            $this->validate($request, [
                'judul' => 'sometimes|required|string|max:255',
                'isi' => 'sometimes|required|string',
                'gambar' => 'nullable|string',
                'tanggal_publikasi' => 'sometimes|required|date',
            ]);

            $informasi->update($request->only(['judul', 'isi', 'gambar', 'tanggal_publikasi']));
            return response()->json([
                'status' => 'success',
                'data' => $informasi,
                'message' => 'Informasi updated successfully',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Informasi not found',
        ], 404);
    }

    public function destroy($id)
    {
        $informasi = Informasi::find($id);
        if ($informasi) {
            $informasi->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Informasi deleted successfully',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Informasi not found',
        ], 404);
    }
}