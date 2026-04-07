<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->guard('portal')->check()) {
        return redirect()->route('home');
    }

    return view('auth.login');
});

Route::middleware('auth:portal')->group(function () {
    Route::get('/home', [PortalController::class, 'home'])->name('home');
    Route::get('/aspirasi', [PortalController::class, 'aspirasi'])->name('aspirasi');
    Route::post('/aspirasi', [PortalController::class, 'store'])->name('aspirasi.store');
    Route::post('/kategori', [PortalController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/admin/tambah', [PortalController::class, 'createAdmin'])->name('admin.create');
    Route::post('/admin/tambah', [PortalController::class, 'storeAdmin'])->name('admin.store');
    Route::delete('/admin/{admin}', [PortalController::class, 'destroyAdmin'])->name('admin.destroy');
    Route::put('/aspirasi/{inputAspirasi}', [PortalController::class, 'update'])->name('aspirasi.update');
    Route::get('/umpanBalik', [PortalController::class, 'umpanBalik'])->name('umpanBalik');
});
