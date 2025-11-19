<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\SewaController;
use App\Models\Unit;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PenyewaLoginController;
use App\Http\Controllers\PortalController; // Digunakan untuk Dashboard Penyewa
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

// ----------------------------------------------------
// RUTE PUBLIK (LANDING PAGE)
// ----------------------------------------------------
Route::get('/', function () {
    // Menampilkan unit yang tersedia di halaman utama
    $unitTersedia = Unit::where('status', 'tersedia')
                        ->orderBy('name')
                        ->get();

    return view('welcome', compact('unitTersedia'));
});


// ----------------------------------------------------
// RUTE ADMINISTRATOR/PENGELOLA (GUARD DEFAULT 'auth')
// ----------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Utama
    Route::resource('units', UnitController::class)->except(['show']);
    Route::resource('penyewa', PenyewaController::class)->except(['show']);
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    Route::get('/penyewa/export', [PenyewaController::class, 'exportExcel'])->name('penyewa.export');
    Route::get('/pengeluaran/export', [PengeluaranController::class, 'exportExcel'])->name('pengeluaran.export');

    // Manajemen Sewa
    Route::get('/sewa/create', [SewaController::class, 'create'])->name('sewa.create');
    Route::post('/sewa', [SewaController::class, 'store'])->name('sewa.store');
    Route::post('/sewa/{sewa}/stop', [SewaController::class, 'stop'])->name('sewa.stop');

    // Manajemen Tagihan
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/export', [TagihanController::class, 'exportExcel'])->name('tagihan.export');
    Route::post('/bayar/{tagihan}', [TagihanController::class, 'bayar'])->name('tagihan.bayar');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// ----------------------------------------------------
// RUTE PENYEWA (CUSTOM GUARD 'penyewa')
// ----------------------------------------------------

// Otentikasi Penyewa
Route::get('/penyewa/login', [PenyewaLoginController::class, 'create'])
    ->middleware('guest:penyewa')
    ->name('penyewa.login');

Route::post('/penyewa/login', [PenyewaLoginController::class, 'store'])
    ->middleware('guest:penyewa')
    ->name('penyewa.login.store');

Route::post('/penyewa/logout', [PenyewaLoginController::class, 'destroy'])
    ->middleware('auth:penyewa')
    ->name('penyewa.logout');

// Dashboard Portal Penyewa (Setekah login)
Route::get('/portal/dashboard', [PortalController::class, 'index'])
    ->middleware('auth:penyewa')
    ->name('penyewa.dashboard');

// Portal Penyewa (Semua rute di sini memerlukan login penyewa)
Route::middleware(['auth:penyewa'])->prefix('portal')->name('penyewa.')->group(function () {
    
    // Dashboard Penyewa
    Route::get('/dashboard', [PortalController::class, 'index'])->name('dashboard');
    
    // Profil Penyewa
    Route::get('/profil', [PortalController::class, 'editProfil'])->name('profil.edit');
    Route::patch('/profil', [PortalController::class, 'updateProfil'])->name('profil.update');
    
});

// Rute Otentikasi Laravel Bawaan (untuk Admin)
require __DIR__.'/auth.php';