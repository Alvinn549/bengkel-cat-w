<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PekerjaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/do-login', [AuthController::class, 'doLogin'])->name('do-login');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/do-register', [AuthController::class, 'doRegister'])->name('doRegister');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/change-email', [VerificationController::class, 'changeEmail'])->name('verification.change-email');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');

    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/my-kendaraan/{idPelanggan}', [DashboardController::class, 'myKendaraan'])->name('dashboard.my-kendaraan');

        Route::get('/dashboard/my-transaksi/{idPelanggan}', [DashboardController::class, 'myTransaksi'])->name('dashboard.my-transaksi');
        Route::get('/dashboard/my-transaksi/detail/{transaksi}', [DashboardController::class, 'detailMyTransaksi'])->name('dashboard.my-transaksi-detail');

        Route::get('/dashboard/history-transaksi/{idPelanggan}', [DashboardController::class, 'historyTransaksi'])->name('dashboard.history-transaksi');
        Route::get('/dashboard/history-transaksi/detail/{transaksi}', [DashboardController::class, 'detailHistoryTransaksi'])->name('dashboard.detail-history-transaksi');

        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::put('/profil/{id}/change', [ProfilController::class, 'update'])->name('profil.update');
        Route::post('/profil/change-email', [ProfilController::class, 'changeEmail'])->name('profil.change-email');

        Route::get('/admin/data-table', [AdminController::class, 'dataTableAdmin'])->name('admin.data-table');
        Route::resource('admin', AdminController::class);

        Route::get('/pekerja/data-table', [PekerjaController::class, 'dataTablePekerja'])->name('pekerja.data-table');
        Route::resource('pekerja', PekerjaController::class);

        Route::get('/pelanggan/data-table', [PelangganController::class, 'dataTablePelanggan'])->name('pelanggan.data-table');
        Route::resource('pelanggan', PelangganController::class);

        Route::get('/kendaraan/data-table', [KendaraanController::class, 'dataTableKendaraan'])->name('kendaraan.data-table');
        Route::resource('kendaraan', KendaraanController::class);

        Route::resource('perbaikan', PerbaikanController::class);
        Route::resource('tipe', TipeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('merek', MerekController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('/transaksi/data-table', [TransaksiController::class, 'dataTableTransaksi'])->name('transaksi.data-table');
        Route::resource('transaksi', TransaksiController::class);

        Route::resource('settings', SettingsController::class);
        Route::resource('laporan', LaporanController::class);
    });
});
