<?php

use Illuminate\Http\Request;
use App\Exports\PanelsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PJUController;
use App\Http\Controllers\GeoJsonController;
use App\Http\Controllers\RiwayatPJUController;
use App\Http\Controllers\RiwayatPanelController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KonstruksiController;

//Company Profile
Route::get('/userpju', [PJUController::class, 'index']);
Route::get('/userpanel', [PanelController::class, 'index']);
Route::get('/userpemetaanfilter', [PJUController::class, 'pemetaanMaps']);
Route::get('/userkecamatanlist', [PJUController::class, 'getKecamatanList']);

// Route::get('/userberita', [BeritaController::class, 'index']);
Route::get('/userberita', [BeritaController::class, 'getBeritaPagination']);
Route::get('/userberitaterbaru', [BeritaController::class, 'getBeritaTerbaru']);
Route::get('/userberita/{slug}', [BeritaController::class, 'showtextrandom']);
Route::get('/userteams', [TeamController::class, 'index']); 
Route::get('/geojson', [GeoJsonController::class, 'getGeoJson']);
Route::get('/geojson/all', [GeoJsonController::class, 'getAllGeoJson']);

//Auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
});

//Admin
Route::middleware('auth:sanctum')->group(function () {
    //Berita
    Route::get('/berita', [BeritaController::class, 'index']); 
    Route::post('/berita', [BeritaController::class, 'store']);
    Route::get('/berita/{id}', [BeritaController::class, 'show']);
    Route::post('/berita/{id}', [BeritaController::class, 'update']);
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy']);

    //Team
    Route::get('/teams', [TeamController::class, 'index']); 
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams/{id}', [TeamController::class, 'show']);
    Route::post('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);

    //Panel
    Route::get('/panels', [PanelController::class, 'index']); 
    Route::get('/dropdownpanels', [PanelController::class, 'dropdownPanels']);
    Route::post('/panels', [PanelController::class, 'store']);
    Route::post('/panels/{id}', [PanelController::class, 'update']);
    Route::delete('/panels/{id}', [PanelController::class, 'destroy']);
    Route::post('/panels/import', [PanelController::class, 'import']);

    //PJU
    Route::get('/pjus', [PJUController::class, 'index']); 
    // Route::get('/pjus/{id}', [PJUController::class, 'show']); 
    Route::post('/pjus', [PJUController::class, 'store']);
    Route::post('/pjus/{id}', [PJUController::class, 'update']);
    Route::delete('/pjus/{id}', [PJUController::class, 'destroy']);
    Route::get('/kecamatan-list', [PJUController::class, 'getKecamatanList']);
    Route::get('/filter-pju-by-panel', [PJUController::class, 'filterDataByPanel']);
    Route::get('/export/pju', [PJUController::class, 'exportDataPJU']);

    //Riwayat PJU
    Route::get('/riwayat-pju/{pju_id}', [RiwayatPJUController::class, 'index']);
    Route::post('/riwayat-pju', [RiwayatPJUController::class, 'store']);
    Route::put('/riwayat-pju/{id}', [RiwayatPJUController::class, 'update']);
    Route::delete('/riwayat-pju/{id}', [RiwayatPJUController::class, 'destroy']);

    //Riwayat Panel
    Route::get('/riwayat-panel/{panel_id}', [RiwayatPanelController::class, 'index']);
    Route::post('/riwayat-panel', [RiwayatPanelController::class, 'store']);
    Route::put('/riwayat-panel/{id}', [RiwayatPanelController::class, 'update']);
    Route::delete('/riwayat-panel/{id}', [RiwayatPanelController::class, 'destroy']);

    //Konstruksi
    Route::get('/konstruksi', [KonstruksiController::class, 'index']);
    Route::post('/konstruksi', [KonstruksiController::class, 'store']);
    Route::get('/konstruksi/{id}', [KonstruksiController::class, 'show']);
    Route::put('/konstruksi/{id}', [KonstruksiController::class, 'update']);
    Route::delete('/konstruksi/{id}', [KonstruksiController::class, 'destroy']);
    Route::get('/export/konstruksi', [ExportController::class, 'exportDataKonstruksi']);
    Route::post('/import/konstruksi', [ImportController::class, 'import']);

    //Dashboard
    Route::get('/dashboard-data', [PJUController::class, 'getDashboardData']);

    //Export
    Route::get('/export/pju', [ExportController::class, 'exportDataPJU']);
    Route::get('/export/panel', [ExportController::class, 'exportDataPanel']);
});

Route::get('/export-riwayat-pju/all', [ExportController::class, 'exportAll']);
Route::get('/export-riwayat-pju/pengaduan', [ExportController::class, 'exportPengaduan']);
Route::get('/export-riwayat-pju/riwayat', [ExportController::class, 'exportRiwayat']);
Route::get('/export-riwayat-pju/riwayat/{pjuId}', [ExportController::class, 'exportByPJU']);




// Route::get('/export-riwayat-panel/all', [ExportController::class, 'exportAllPenel']);
// Route::get('/export-riwayat-panel/pengaduan', [ExportController::class, 'exportPengaduanPanel']);
// Route::get('/export-riwayat-panel/riwayat', [ExportController::class, 'exportRiwayatPanel']);
// Route::get('/export-riwayat-panel/riwayat/{panelId}', [ExportController::class, 'exportByPJUPanel']);