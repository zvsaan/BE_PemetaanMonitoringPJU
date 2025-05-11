<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json($slides);
    }

    public function adminIndex()
    {
        $slides = HeroSlide::orderBy('order')->get();
        return response()->json($slides);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'is_active' => 'required|boolean'
        ]);

        $imagePath = $request->file('image')->store('hero_slides', 'public');

        $slide = HeroSlide::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'order' => $validated['order'],
            'is_active' => $validated['is_active']
        ]);

        return response()->json($slide, 201);
    }

    public function update(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slide->image_path);
            $validated['image_path'] = $request->file('image')->store('hero_slides', 'public');
        }

        $slide->update($validated);

        return response()->json($slide);
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->update(['is_active' => !$slide->is_active]);

        return response()->json([
            'message' => 'Status active berhasil diubah',
            'is_active' => $slide->is_active
        ]);
    }

    public function destroy($id)
    {
        $slide = HeroSlide::findOrFail($id);
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        return response()->json(['message' => 'Slide deleted successfully']);
    }
}