<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataKonstruksiImport;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        ini_set('max_execution_time', 300);
        
        // Validasi file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Import file menggunakan Laravel Excel
            Excel::import(new DataKonstruksiImport, $request->file('file'));

            // Response berhasil
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diimpor ke database.',
            ], 200);
        } catch (\Exception $e) {
            // Response jika terjadi error
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}