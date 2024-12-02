<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeoJsonController extends Controller
{
    public function getGeoJson(Request $request)
    {
        $kecamatan = $request->query('kecamatan');
        if (!$kecamatan) {
            return response()->json(['error' => 'Kecamatan parameter is required'], 400);
        }
    
        $filePath = "geojson/{$kecamatan}.geojson";
    
        if (Storage::disk('public')->exists($filePath)) {
            return response()->file(storage_path("app/public/{$filePath}"));
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }    

    public function getAllGeoJson()
    {
        $files = Storage::disk('public')->files('geojson');
        $geoJsonData = [];

        foreach ($files as $file) {
            $data = json_decode(Storage::disk('public')->get($file), true);
            if ($data) {
                $geoJsonData[] = $data;
            }
        }

        return response()->json($geoJsonData);
    }
}