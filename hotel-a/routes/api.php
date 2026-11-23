<?php
use App\Http\Controllers\{BookingController, RoomController};
use Illuminate\Support\Facades\Route;

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/available', [RoomController::class, 'available']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
