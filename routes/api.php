<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PJUController;

//Auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});

//User
Route::get('/pjus', [PJUController::class, 'index']); 

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
    Route::post('/panels', [PanelController::class, 'store']);
    Route::get('/panels/{id}', [PanelController::class, 'show']);
    Route::post('/panels/{id}', [PanelController::class, 'update']);
    Route::delete('/panels/{id}', [PanelController::class, 'destroy']);

    //PJU
    Route::get('/pjus', [PJUController::class, 'index']); 
    Route::post('/pjus', [PJUController::class, 'store']);
    Route::get('/pjus/{id}', [PJUController::class, 'show']);
    Route::post('/pjus/{id}', [PJUController::class, 'update']);
    Route::delete('/pjus/{id}', [PJUController::class, 'destroy']);
});