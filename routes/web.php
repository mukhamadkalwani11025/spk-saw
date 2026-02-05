<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\MakananKriteriaController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\DashboardController;

// auth
Auth::routes();
Route::get('/', function () {return redirect('/login');});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// controller tabel
Route::resource('makanan', MakananController::class);
Route::resource('kriteria', KriteriaController::class);
Route::resource('makanan_kriteria',MakananKriteriaController::class);
Route::resource('rekomendasi', RekomendasiController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/hitung', [RekomendasiController::class, 'hitung'])->name('rekomendasi.hitung');
    Route::delete('/rekomendasi/{id}', [RekomendasiController::class, 'destroy'])->name('rekomendasi.destroy');
});
