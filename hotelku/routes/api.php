<?php
use App\Http\Controllers\HotelKuController;
use Illuminate\Support\Facades\Route;

Route::get('/hotels', [HotelKuController::class, 'hotels']);
Route::get('/search', [HotelKuController::class, 'search']);
Route::post('/bookings', [HotelKuController::class, 'store']);
Route::get('/bookings/{id}', [HotelKuController::class, 'show']);
Route::delete('/bookings/{id}', [HotelKuController::class, 'destroy']);
