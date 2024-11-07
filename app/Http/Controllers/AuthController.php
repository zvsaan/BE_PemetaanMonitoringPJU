<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Login gagal. Silakan periksa kembali data Anda.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user(),
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
            'foto' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->input('name');
        $user->username = $request->input('username');

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('public/fotos');
            $user->foto = basename($imagePath);
        }

        // Handle password update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }
}
