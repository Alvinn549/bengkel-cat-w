<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PekerjaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/do-login', [AuthController::class, 'doLogin'])->name('do-login');
});

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/data-table', [AdminController::class, 'dataTableAdmin'])->name('admin.data-table');
    Route::resource('admin', AdminController::class);

    Route::get('/pekerja/data-table', [PekerjaController::class, 'dataTablePekerja'])->name('pekerja.data-table');
    Route::resource('pekerja', PekerjaController::class);

    Route::get('/pelanggan/data-table', [PelangganController::class, 'dataTablePelanggan'])->name('pelanggan.data-table');
    Route::resource('pelanggan', PelangganController::class);

    Route::get('/kendaraan/data-table', [KendaraanController::class, 'dataTableKendaraan'])->name('kendaraan.data-table');
    Route::resource('kendaraan', KendaraanController::class);

    Route::resource('perbaikan', PerbaikanController::class);

    Route::get('/transaksi/data-table', [TransaksiController::class, 'dataTableTransaksi'])->name('transaksi.data-table');
    Route::resource('transaksi', TransaksiController::class);
});
