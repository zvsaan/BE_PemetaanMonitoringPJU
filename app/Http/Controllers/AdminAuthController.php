<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Fungsi login untuk Admin
    public function login(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba otentikasi menggunakan username dan password
        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Membuat token Sanctum untuk admin
        $token = $admin->createToken('admin-token')->plainTextToken;

        // Return token beserta data admin
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'admin' => [
                'id_admin' => $admin->id_admin,
                'name' => $admin->name,
                'username' => $admin->username,
            ],
        ], 200);
    }

    // Fungsi logout untuk Admin
    public function logout(Request $request)
    {
        // Menghapus token admin yang sedang aktif
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful'], 200);
    }
}