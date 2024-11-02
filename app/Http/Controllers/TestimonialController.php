<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return response()->json([
            'status' => 'success',
            'data' => $testimonials,
        ], 200);
    }

    public function show($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            return response()->json([
                'status' => 'success',
                'data' => $testimonial,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Testimonial not found',
        ], 404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $testimonial = Testimonial::create($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $testimonial,
            'message' => 'Testimonial created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $this->validate($request, [
                'nama' => 'sometimes|required|string|max:255',
                'isi' => 'sometimes|required|string',
            ]);

            $testimonial->update($request->only(['nama', 'isi']));
            return response()->json([
                'status' => 'success',
                'data' => $testimonial,
                'message' => 'Testimonial updated successfully',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Testimonial not found',
        ], 404);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $testimonial->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Testimonial deleted successfully',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Testimonial not found',
        ], 404);
    }
}