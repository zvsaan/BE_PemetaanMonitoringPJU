<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NavbarItem;

class NavbarController extends Controller
{
    // Get published items for frontend
    public function index()
    {
        $items = NavbarItem::with(['children' => function($query) {
                    $query->where('is_published', true)
                          ->orderBy('order');
                }])
                ->whereNull('parent_id')
                ->where('is_published', true)
                ->orderBy('order')
                ->get();
                
        return response()->json($items);
    }

    // Get all items for admin panel
    public function adminIndex()
    {
        $items = NavbarItem::with(['children' => function($query) {
                    $query->orderBy('order');
                }])
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
                
        return response()->json($items);
    }

    // Update item
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:link,dropdown',
            'parent_id' => 'nullable|exists:navbar_items,id',
            'order' => 'sometimes|required|integer',
            'is_published' => 'sometimes|boolean'
        ]);

        $item = NavbarItem::findOrFail($id);
        $item->update($validated);

        return response()->json($item);
    }

    // Toggle publish status
    public function togglePublish($id)
    {
        $item = NavbarItem::findOrFail($id);
        $item->update(['is_published' => !$item->is_published]);

        return response()->json([
            'message' => 'Status publish berhasil diubah',
            'is_published' => $item->is_published
        ]);
    }

    // Delete item
    public function destroy($id)
    {
        $item = NavbarItem::findOrFail($id);
        
        // Hapus semua child items jika ada
        if ($item->children->count() > 0) {
            $item->children()->delete();
        }
        
        $item->delete();

        return response()->json(['message' => 'Item berhasil dihapus']);
    }
}