<?php
use App\Http\Controllers\{KamarController, PemesananController};
use Illuminate\Support\Facades\Route;

Route::get('/kamar', [KamarController::class, 'index']);
Route::get('/kamar/tersedia', [KamarController::class, 'tersedia']);
Route::post('/pemesanan', [PemesananController::class, 'store']);
Route::get('/pemesanan/{id}', [PemesananController::class, 'show']);
Route::delete('/pemesanan/{id}', [PemesananController::class, 'destroy']);
