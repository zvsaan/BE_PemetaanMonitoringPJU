<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'photo_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Simpan foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo_url')) {
            $photoPath = $request->file('photo_url')->store('teams', 'public'); // Menyimpan di folder 'public/teams'
        }

        $team = Team::create([
            'name' => $request->name,
            'position' => $request->position,
            'description' => $request->description,
            'photo_url' => $photoPath ? asset('storage/' . $photoPath) : null, // Simpan path lengkap gambar
        ]);

        return response()->json($team, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'position' => 'string|max:255',
            'description' => 'required|string',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update data
        $team->fill($request->only(['name', 'position', 'description']));

        // Update foto jika ada foto baru
        if ($request->hasFile('photo_url')) {
            // Hapus foto lama jika ada
            if ($team->photo_url) {
                $oldPhotoPath = str_replace(asset('storage/'), '', $team->photo_url);
                Storage::disk('public')->delete($oldPhotoPath);
            }

            // Simpan foto baru
            $photoPath = $request->file('photo_url')->store('teams', 'public');
            $team->photo_url = asset('storage/' . $photoPath);
        }

        $team->save();

        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        // Hapus foto jika ada
        if ($team->photo_url) {
            $photoPath = str_replace(asset('storage/'), '', $team->photo_url);
            Storage::disk('public')->delete($photoPath);
        }

        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }
}