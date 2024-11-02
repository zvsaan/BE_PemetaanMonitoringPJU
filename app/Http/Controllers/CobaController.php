<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coba;

class CobaController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel tiang_lampu
        $tiangLampu = Coba::all();
        return response()->json($tiangLampu);
    }
}
