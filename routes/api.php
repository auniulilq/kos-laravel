<?php
// File: routes/api.php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PaymentController;

// Rute ini yang akan kamu masukkan ke Dashboard Midtrans
// Contoh URL: https://xxxx.ngrok-free.app/api/midtrans/notification
Route::post('/midtrans/notification', [BookingController::class, 'handleNotification']);
Route::post('/midtrans/callback', [BookingController::class, 'handleNotification']);
