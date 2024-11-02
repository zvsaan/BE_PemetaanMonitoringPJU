<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PjuController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\CobaController;

//Auth
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');

// Route untuk operasi CRUD PJU
Route::get('/pjus', [PjuController::class, 'index']);
Route::get('/pjus/{id}', [PjuController::class, 'show']);
Route::post('/pjus', [PjuController::class, 'store']);
Route::put('/pjus/{id}', [PjuController::class, 'update']);
Route::delete('/pjus/{id}', [PjuController::class, 'destroy']);

// CRUD Testimoni
Route::get('/testimonial', [TestimonialController::class, 'index']);
Route::get('/testimonial/{id}', [TestimonialController::class, 'show']);
Route::post('/testimonial', [TestimonialController::class, 'store']);
Route::put('/testimonial/{id}', [TestimonialController::class, 'update']);
Route::delete('/testimonial/{id}', [TestimonialController::class, 'destroy']);

// CRUD Informasi
Route::get('/informasi', [InformasiController::class, 'index']);
Route::get('/informasi/{id}', [InformasiController::class, 'show']);
Route::post('/informasi', [InformasiController::class, 'store']);
Route::put('/informasi/{id}', [InformasiController::class, 'update']);
Route::delete('/informasi/{id}', [InformasiController::class, 'destroy']);

//nyoba
Route::get('/coba', [CobaController::class, 'index']);