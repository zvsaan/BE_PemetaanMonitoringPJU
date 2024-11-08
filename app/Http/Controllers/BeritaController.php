<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beritas = Berita::all();
        return response()->json($beritas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'author' => 'nullable|string|max:255',
            'published_date' => 'required|date',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('berita', 'public'); // Menyimpan di folder 'public/berita'
        }

        $berita = Berita::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'published_date' => $request->published_date,
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null, // Simpan path lengkap gambar
            'status' => $request->status,
        ]);

        return response()->json($berita, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita not found'], 404);
        }

        return response()->json($berita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $berita = Berita::find($id);

    if (!$berita) {
        return response()->json(['message' => 'Berita not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'string|max:255',
        'content' => 'string',
        'author' => 'nullable|string|max:255',
        'published_date' => 'date',
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        'status' => 'in:draft,published,archived',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Update data
    $berita->fill($request->only(['title', 'content', 'author', 'published_date', 'status']));

    // Update gambar jika ada gambar baru
    if ($request->hasFile('image_url')) {
        // Hapus gambar lama jika ada
        if ($berita->image_url) {
            $oldImagePath = str_replace(asset('storage/'), '', $berita->image_url);
            Storage::disk('public')->delete($oldImagePath);
        }

        // Simpan gambar baru
        $imagePath = $request->file('image_url')->store('berita', 'public');
        $berita->image_url = asset('storage/' . $imagePath);
    }

    $berita->save();

    return response()->json($berita);
}


    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita not found'], 404);
        }

        if ($berita->image_url) {
            $imagePath = str_replace(asset('storage/'), '', $berita->image_url);
            Storage::disk('public')->delete($imagePath); 
        }

        $berita->delete();
        return response()->json(['message' => 'Berita deleted successfully']);
    }
}