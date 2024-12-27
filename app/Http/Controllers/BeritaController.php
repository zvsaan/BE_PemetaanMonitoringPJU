<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::all();
        return response()->json($beritas);
    }

    public function getBeritaPagination(Request $request)
    {
        $berita = Berita::orderBy('published_date', 'desc')->paginate(10);

        return response()->json($berita);
    }

    public function getBeritaTerbaru()
    {
        $berita = Berita::orderBy('published_date', 'desc')->take(3)->get();

        return response()->json($berita);
    }

    public function showtextrandom($slug)
    {
        $berita = Berita::where('slug', $slug)->first();

        if (!$berita) {
            return response()->json(['message' => 'Berita not found'], 404);
        }

        return response()->json($berita);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagePath = $request->file('image_url')->store('berita', 'public');

            $berita = Berita::create([
                'title' => $request->title,
                'content' => $request->content,
                'author' => $request->author,
                'published_date' => $request->published_date,
                'image_url' => asset('storage/' . $imagePath),
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'Berita berhasil disimpan',
                'data' => $berita,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan berita',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'content' => 'string',
            'author' => 'string|max:255',
            'published_date' => 'date',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $berita->fill($request->only(['title', 'content', 'author', 'published_date', 'status']));

        if ($request->hasFile('image_url')) {
            if ($berita->image_url) {
                $oldImagePath = str_replace(asset('storage/'), '', $berita->image_url);
                Storage::disk('public')->delete($oldImagePath);
            }

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