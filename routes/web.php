<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'ringkasan'])->name('ringkasan');
Route::get('/analisis', [DashboardController::class, 'analisis'])->name('analisis');
Route::post('/analisis', [ScanController::class, 'store'])->middleware('throttle:20,1')->name('analisis.store');
Route::get('/riwayat', [DashboardController::class, 'riwayat'])->name('riwayat');
Route::get('/sistem', [DashboardController::class, 'sistem'])->name('sistem');
Route::get('/proyek', [DashboardController::class, 'proyek'])->name('proyek');
