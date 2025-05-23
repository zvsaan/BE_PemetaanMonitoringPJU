<?php

namespace App\Http\Controllers;

use App\Models\KwhReading;
use App\Models\DataPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageServiceProvider;
use Carbon\Carbon;

class KwhReadingController extends Controller
{
    public function index(Request $request)
    {
        $query = KwhReading::with(['panel', 'user'])
            ->orderBy('created_at', 'desc');
            
        // Filter by panel if provided
        if ($request->has('panel_id')) {
            $query->where('panel_id', $request->panel_id);
        }
        
        // Filter by month if provided
        if ($request->has('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        
        // Filter by year if provided
        if ($request->has('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        $readings = $query->get();
        return response()->json($readings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panel_id' => 'required|exists:data_panels,id_panel',
            'kwh_number' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        try {
            if ($request->hasFile('image')) {
                $data['image_path'] = $this->storeImageWithWatermark(
                    $request->file('image'), 
                    $request->panel_id,
                    $data['kwh_number']
                );
            }

            $reading = KwhReading::create($data);

            return response()->json([
                'message' => 'Catatan KWH berhasil disimpan',
                'data' => $reading
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reading = KwhReading::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kwh_number' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        try {
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($reading->image_path) {
                    Storage::delete('public/' . $reading->image_path);
                }
                
                $data['image_path'] = $this->storeImageWithWatermark(
                    $request->file('image'),
                    $reading->panel_id,
                    $data['kwh_number'] ?? $reading->kwh_number
                );
            }

            $reading->update($data);

            return response()->json([
                'message' => 'Catatan KWH berhasil diperbarui',
                'data' => $reading
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan gambar dengan watermark lengkap
     */
    protected function storeImageWithWatermark($image, $panelId, $kwhNumber)
    {
        $panel = DataPanel::find($panelId);
        
        $watermarkLines = [
            'NO: ' . ($panel->no_app ?? 'N/A'),
            'LOK: ' . ($panel->nama_jalan ?? 'Lokasi tidak diketahui'),
            'KWH: ' . $kwhNumber,
            'TGL: ' . Carbon::now()->format('d/m/Y H:i')
        ];
        
        $imageName = 'kwh_' . time() . '.' . $image->getClientOriginalExtension();
        $storagePath = 'public/kwh_images/' . $imageName;
        $fullPath = storage_path('app/' . $storagePath);
    
        if (!Storage::exists('public/kwh_images')) {
            Storage::makeDirectory('public/kwh_images');
        }
    
        $sourcePath = $image->getRealPath();
        $imageType = exif_imagetype($sourcePath);
    
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            default:
                throw new \Exception("Hanya format JPEG dan PNG yang didukung");
        }
    
        // Dapatkan dimensi gambar
        $imageWidth = imagesx($sourceImage);
        $imageHeight = imagesy($sourceImage);
        
        // Hitung ukuran font berdasarkan lebar gambar (dinamis)
        $baseFontSize = $imageWidth / 40; // Ukuran font dasar relatif terhadap lebar gambar
        $fontSize = min(max($baseFontSize, 14), 24); // Batasi antara 14-24px
        
        $textColor = imagecolorallocate($sourceImage, 255, 255, 255);
        $bgColor = imagecolorallocatealpha($sourceImage, 0, 0, 0, 60);
        
        $fontPath = public_path('fonts/arial.ttf');
        $useTTF = file_exists($fontPath);
        
        // Hitung dimensi watermark
        $padding = $imageWidth / 100; // Padding relatif
        $lineHeight = $fontSize * 1.5;
        $watermarkHeight = (count($watermarkLines) * $lineHeight) + ($padding * 2);
        $watermarkWidth = 0;
        
        // Hitung lebar maksimum teks
        foreach ($watermarkLines as $line) {
            if ($useTTF) {
                $box = imagettfbbox($fontSize, 0, $fontPath, $line);
                $lineWidth = $box[2] - $box[0];
            } else {
                $lineWidth = strlen($line) * imagefontwidth($fontSize);
            }
            
            $watermarkWidth = max($watermarkWidth, $lineWidth);
        }
        
        $watermarkWidth += ($padding * 2);
        
        // Posisi watermark (kanan bawah dengan margin)
        $margin = $imageWidth / 20; // Margin relatif
        $x = $imageWidth - $watermarkWidth - $margin;
        $y = $imageHeight - $watermarkHeight - $margin;
        
        // Gambar background
        imagefilledrectangle(
            $sourceImage, 
            $x, $y, 
            $x + $watermarkWidth, $y + $watermarkHeight, 
            $bgColor
        );
        
        // Tambahkan teks watermark
        foreach ($watermarkLines as $i => $line) {
            $textY = $y + $padding + ($i * $lineHeight);
            
            if ($useTTF) {
                imagettftext(
                    $sourceImage, 
                    $fontSize, 
                    0, 
                    $x + $padding, 
                    $textY, 
                    $textColor, 
                    $fontPath, 
                    $line
                );
            } else {
                imagestring(
                    $sourceImage, 
                    $fontSize, 
                    $x + $padding, 
                    $textY - imagefontheight($fontSize), 
                    $line, 
                    $textColor
                );
            }
        }
    
        // Kompresi gambar sebelum disimpan (untuk ukuran besar)
        $quality = 80; // Kualitas gambar (0-100)
        
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($sourceImage, $fullPath, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($sourceImage, $fullPath, round(9 * $quality / 100));
                break;
        }
    
        imagedestroy($sourceImage);
        return str_replace('public/', '', $storagePath);
    }

    public function destroy($id)
    {
        $reading = KwhReading::findOrFail($id);
        
        if ($reading->image_path) {
            Storage::delete('public/' . $reading->image_path);
        }
        
        $reading->delete();

        return response()->json([
            'message' => 'KWH reading deleted successfully'
        ]);
    }

    public function panelReadings($panelId)
    {
        $readings = KwhReading::with('user')
            ->where('panel_id', $panelId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($readings);
    }
    
    public function filterOptions()
    {
        $years = KwhReading::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        $panels = DataPanel::select('id_panel', 'no_app')
            ->orderBy('no_app')
            ->get();
            
        return response()->json([
            'years' => $years,
            'panels' => $panels
        ]);
    }
}