<?php

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PJUController;
use App\Exports\PanelsExport;
use App\Http\Controllers\GeoJsonController;
use App\Http\Controllers\RiwayatPJUController;
use App\Http\Controllers\RiwayatPanelController;
use App\Http\Controllers\ExportController;

//Auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
});

//Company Profile
Route::get('/userpju', [PJUController::class, 'index']);
Route::get('/userpanel', [PanelController::class, 'index']);
Route::get('/userpemetaanfilter', [PJUController::class, 'pemetaanMaps']);
Route::get('/userkecamatanlist', [PJUController::class, 'getKecamatanList']);
Route::get('/userberita', [BeritaController::class, 'index']);
Route::get('/userberita/{slug}', [BeritaController::class, 'showtextrandom']);
Route::get('/userteams', [TeamController::class, 'index']); 
Route::get('/geojson', [GeoJsonController::class, 'getGeoJson']);
Route::get('/geojson/all', [GeoJsonController::class, 'getAllGeoJson']);

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
    Route::post('/pjus', [PJUController::class, 'store']);
    Route::post('/pjus/{id}', [PJUController::class, 'update']);
    Route::delete('/pjus/{id}', [PJUController::class, 'destroy']);
    Route::get('/kecamatan-list', [PJUController::class, 'getKecamatanList']);
    Route::get('/filter-pju-by-panel', [PJUController::class, 'filterDataByPanel']);
    Route::get('/export/pju', [PJUController::class, 'exportDataPJU']);

    //Dashboard
    // Route::get('/dashboard-data', [PJUController::class, 'getDashboardData']);

    //Export
    Route::get('/export/pju', [ExportController::class, 'exportDataPJU']);
    Route::get('/export/panel', [ExportController::class, 'exportDataPanel']);
    
});
Route::get('/dashboard-data', [PJUController::class, 'getDashboardData']);

Route::get('/export/riwayat-panel/{id}', [ExportController::class, 'exportRiwayatPanel']);

//Riwayat PJU
Route::get('/riwayat-pju', [RiwayatPJUController::class, 'index']); 
Route::get('/riwayat-pju/{id}', [RiwayatPJUController::class, 'getRiwayatByPJU']); 
Route::post('/riwayat-pju', [RiwayatPJUController::class, 'store']);
Route::post('/riwayat-pju/{id}', [RiwayatPJUController::class, 'update']);
Route::delete('/riwayat-pju/{id}', [RiwayatPJUController::class, 'destroy']);

//Riwayat Panel
Route::get('/riwayat-panel', [RiwayatPanelController::class, 'index']); 
Route::get('/riwayat-panel/{id}', [RiwayatPanelController::class, 'getRiwayatByPanel']); 
Route::post('/riwayat-panel', [RiwayatPanelController::class, 'store']);
Route::post('/riwayat-panel/{id}', [RiwayatPanelController::class, 'update']);
Route::delete('/riwayat-panel/{id}', [RiwayatPanelController::class, 'destroy']);