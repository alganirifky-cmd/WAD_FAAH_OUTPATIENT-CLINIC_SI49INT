<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrugController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/drugs');

// ---- Login / logout ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
});
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

// ---- Harus login ----
Route::middleware('auth')->group(function () {

    // admin DAN staff boleh melihat daftar obat
    Route::get('/drugs', [DrugController::class, 'index'])->name('drugs.index');

    // hanya ADMIN yang boleh tambah / ubah / hapus
    Route::middleware('role:admin')->group(function () {
        Route::resource('drugs', DrugController::class)->except(['index', 'show']);
    });
});
