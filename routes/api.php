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
use App\Http\Controllers\RiwayatPJUController;
use App\Http\Controllers\RiwayatPanelController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KonstruksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PengaduanController;

// Route::get('/userberita', [BeritaController::class, 'index']);
Route::get('/userberita', [BeritaController::class, 'getBeritaPagination']);
Route::get('/userberitaterbaru', [BeritaController::class, 'getBeritaTerbaru']);
Route::get('/userberita/{slug}', [BeritaController::class, 'showtextrandom']);
Route::get('/userteams', [TeamController::class, 'index']); 

//Auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
});

//SuperAdmin
Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard-data', [SuperAdminController::class, 'getDashboardData']);
    Route::get('/users', [SuperAdminController::class, 'index']);
    Route::post('/users', [SuperAdminController::class, 'store']);
    Route::get('/users/{id}', [SuperAdminController::class, 'show']);
    Route::put('/users/{id}', [SuperAdminController::class, 'update']);
    Route::delete('/users/{id}', [SuperAdminController::class, 'destroy']);
    Route::get('/superadmin/dashboard-data', [DashboardController::class, 'dashboardUserData']); 
});

//Admin
Route::middleware('auth:sanctum', 'role:admin')->group(function () {
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
    Route::post('/panels', [PanelController::class, 'store']);
    Route::post('/panels/{id}', [PanelController::class, 'update']);
    Route::delete('/panels/{id}', [PanelController::class, 'destroy']);

    //Pemetaan
    Route::get('/panels-with-status', [RiwayatPanelController::class, 'getPanelsWithStatus']);
    Route::get('/pjus-with-status', [RiwayatPJUController::class, 'getPjusWithStatus']);

    //PJU
    Route::get('/pjus', [PJUController::class, 'index']); 
    Route::post('/pjus', [PJUController::class, 'store']);
    Route::post('/pjus/{id}', [PJUController::class, 'update']);
    Route::delete('/pjus/{id}', [PJUController::class, 'destroy']);

    //Filter
    Route::get('/kecamatan-list', [PJUController::class, 'getKecamatanList']);
    Route::get('/filter-pju-by-panel', [PJUController::class, 'filterDataByPanel']);
    Route::get('/dropdownpanels', [PanelController::class, 'dropdownPanels']);

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

    //Import
    Route::post('/import/konstruksi', [ImportController::class, 'import']);
    Route::post('/import/riwayat-pju', [ImportController::class, 'importRiwayatPJU']);
    Route::post('/import/riwayat-panel', [ImportController::class, 'importRiwayatPanel']);

    //Dashboard
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

    //Export PJU
    Route::get('/export/pju', [ExportController::class, 'exportDataPJU']);
    Route::get('/export/panel', [ExportController::class, 'exportDataPanel']);
    Route::get('/export/konstruksi', [ExportController::class, 'exportDataKonstruksi']);

    //Riwayat BY ID
    Route::get('/export-riwayat-pju/riwayat/{pjuId}', [ExportController::class, 'exportByPJU']);
    Route::get('/export-riwayat-panel/riwayat/{pjuId}', [ExportController::class, 'exportByPanel']);

    //PJU Panel Pengaduan
    Route::get('/export-riwayat-pju/all', [ExportController::class, 'exportAllPJU']);
    Route::get('/export-riwayat-panel/all', [ExportController::class, 'exportAllPanel']);

    Route::get('/export-riwayat-pju/pengaduan', [ExportController::class, 'exportPengaduanPJU']);
    Route::get('/export-riwayat-panel/pengaduan', [ExportController::class, 'exportPengaduanPanel']);

    Route::get('/export-riwayat-pju/riwayat', [ExportController::class, 'exportRiwayatPJU']);
    Route::get('/export-riwayat-panel/riwayat', [ExportController::class, 'exportRiwayatPanel']);

    //Pengaduan
    Route::post('/pengaduan', [PengaduanController::class, 'create_pengaduan']);  // To create a complaint
    Route::get('/pengaduan/export_excel', [PengaduanController::class, 'exportExcel']); 
    Route::get('/export-template', [PengaduanController::class, 'exportTemplate']);
    Route::get('/pengaduan/exportWord',[PengaduanController::class, 'exportToWord']);
    Route::post('/import-pengaduan', [PengaduanController::class, 'import_pengaduan']); 
    Route::get('/panel/{panel_id}/validate', [PengaduanController::class, 'validatePanel']);
    Route::put('/pengaduan/{id_pengaduan}', [PengaduanController::class, 'update_pengaduan']);  // To update a complaint by ID
    Route::get('/pengaduan', [PengaduanController::class, 'get_pengaduan']); // To view all complaints
    Route::delete('/pengaduan/{id_pengaduan}', [PengaduanController::class, 'delete_pengaduan']);  // To delete a complaint by ID
    Route::get('/pengaduan/count', [PengaduanController::class, 'count_pengaduan']);
    Route::get('/pengaduan/{id_pengaduan}', [PengaduanController::class, 'get_detail_pengaduan']); 
});

//Admin
Route::middleware('auth:sanctum', 'role:visitor')->group(function () {
    Route::get('/visitor/panels', [PanelController::class, 'index']); 
    Route::get('/visitor/panels-with-status', [RiwayatPanelController::class, 'getPanelsWithStatus']);
    Route::get('/visitor/pjus-with-status', [RiwayatPJUController::class, 'getPjusWithStatus']);
    Route::get('/visitor/kecamatan-list', [PJUController::class, 'getKecamatanList']);
    Route::get('/visitor/pjus', [PJUController::class, 'index']); 
    Route::get('/visitor/riwayat-pju/{pju_id}', [RiwayatPJUController::class, 'index']);
    Route::get('/visitor/riwayat-panel/{panel_id}', [RiwayatPanelController::class, 'index']);
    Route::get('/visitor/konstruksi', [KonstruksiController::class, 'index']);
    Route::get('/visitor/dashboard-data', [DashboardController::class, 'getDashboardData']);
});