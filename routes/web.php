<?php

use App\Http\Controllers\AduanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes - Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout route - Authenticated only
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Aduan routes - Authenticated users only
Route::middleware(\App\Http\Middleware\CheckAuth::class)->group(function () {
    Route::get('/aduan/create', [AduanController::class, 'create'])->name('aduan.create');
    Route::post('/aduan', [AduanController::class, 'store'])->name('aduan.store');

    // Location API
    Route::post('/api/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('api.reverse-geocode');
});
