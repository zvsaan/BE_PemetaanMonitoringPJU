<?php

namespace App\Http\Controllers;

use App\Models\Pju;
use Illuminate\Http\Request;

class PjuController extends Controller
{
    public function index()
    {
        return response()->json(Pju::all(), 200);
    }

    // Get specific data by ID
    public function show($id)
    {
        $pjus = Pju::find($id);
        if (!$pjus) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($pjus, 200);
    }

    // Create new data
    public function store(Request $request)
    {
        $pjus = Pju::create($request->all());
        return response()->json($pjus, 201);
    }

    // Update existing data
    public function update(Request $request, $id)
    {
        $pjus = Pju::find($id);
        if (!$pjus) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $pjus->update($request->all());
        return response()->json($pjus, 200);
    }

    // Delete data
    public function destroy($id)
    {
        $pjus = Pju::find($id);
        if (!$pjus) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $pjus->delete();
        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
}