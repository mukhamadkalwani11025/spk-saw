<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\MakananKriteriaController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('makanan', MakananController::class);
    Route::resource('kriteria', KriteriaController::class);
    Route::resource('makanan_kriteria', MakananKriteriaController::class);

    // Rekomendasi (SAW)
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/hitung', [RekomendasiController::class, 'hitung'])->name('rekomendasi.hitung');
    Route::delete('/rekomendasi/{id}', [RekomendasiController::class, 'destroy'])->name('rekomendasi.destroy');
});
