<?php

namespace App\Http\Controllers;

use App\Models\KwhReading;
use App\Models\DataPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KwhReadingController extends Controller
{
    public function index()
    {
        $readings = KwhReading::with(['panel', 'user'])->get();
        return response()->json($readings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_id' => 'required|exists:data_panels,id_panel',
            'kwh_number' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/kwh_images');
            $data['image_path'] = str_replace('public/', '', $imagePath);
        }

        $reading = KwhReading::create($data);

        return response()->json([
            'message' => 'KWH reading recorded successfully',
            'data' => $reading
        ], 201);
    }

    public function show($id)
    {
        $reading = KwhReading::with(['panel', 'user'])->findOrFail($id);
        return response()->json($reading);
    }

    public function update(Request $request, $id)
    {
        $reading = KwhReading::findOrFail($id);

        $validator = Validator::make($request->all(), [
            // 'kwh_number' => 'sometimes|required|string',
            'kwh_number' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($reading->image_path) {
                Storage::delete('public/' . $reading->image_path);
            }
            
            $imagePath = $request->file('image')->store('public/kwh_images');
            $data['image_path'] = str_replace('public/', '', $imagePath);
        }

        $reading->update($data);

        return response()->json([
            'message' => 'KWH reading updated successfully',
            'data' => $reading
        ]);
    }

    public function destroy($id)
    {
        $reading = KwhReading::findOrFail($id);
        
        if ($reading->image_path) {
            Storage::delete('public/' . $reading->image_path);
        }
        
        $reading->delete();

        return response()->json([
            'message' => 'KWH reading deleted successfully'
        ]);
    }

    public function panelReadings($panelId)
    {
        $readings = KwhReading::with('user')
            ->where('panel_id', $panelId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($readings);
    }
}