<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPekerjaController;
use App\Http\Controllers\DashboardPelangganController;
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

        Route::prefix('dashboard')->group(function () {
            // ? For Pelanggan
            Route::get('/pelanggan/my-kendaraan/{idPelanggan}', [DashboardPelangganController::class, 'myKendaraan'])->name('dashboard.pelanggan.my-kendaraan');
            Route::get('/pelanggan/my-kendaraan/detail/{kendaraan}', [DashboardPelangganController::class, 'detailMyKendaraan'])->name('dashboard.pelanggan.my-kendaraan-detail');

            Route::get('/pelanggan/my-transaksi/{idPelanggan}', [DashboardPelangganController::class, 'myTransaksi'])->name('dashboard.pelanggan.my-transaksi');
            Route::get('/pelanggan/my-transaksi/detail/{transaksi}', [DashboardPelangganController::class, 'detailMyTransaksi'])->name('dashboard.pelanggan.my-transaksi-detail');

            Route::get('/pelanggan/history-transaksi/{idPelanggan}', [DashboardPelangganController::class, 'historyTransaksi'])->name('dashboard.pelanggan.history-transaksi');
            Route::get('/pelanggan/history-transaksi/detail/{transaksi}', [DashboardPelangganController::class, 'detailHistoryTransaksi'])->name('dashboard.pelanggan.history-transaksi-detail');

            Route::get('/pelanggan/current-perbaikan/{idPelanggan}', [DashboardPelangganController::class, 'currentPerbaikan'])->name('dashboard.pelanggan.current-perbaikan');
            Route::get('/pelanggan/current-perbaikan/detail/{perbaikan}', [DashboardPelangganController::class, 'detailCurrentPerbaikan'])->name('dashboard.pelanggan.current-perbaikan-detail');

            Route::get('/pelanggan/history-perbaikan/{idPelanggan}', [DashboardPelangganController::class, 'historyPerbaikan'])->name('dashboard.pelanggan.history-perbaikan');
            Route::get('/pelanggan/history-perbaikan/detail/{perbaikan}', [DashboardPelangganController::class, 'detailHistoryPerbaikan'])->name('dashboard.pelanggan.history-perbaikan-detail');
            // ? End For Pelanggan

            // ? For Pekerja
            Route::get('/pekerja/proses-perbaikan/{perbaikan}', [DashboardPekerjaController::class, 'prosesPerbaikan'])->name('dashboard.pekerja.proses-perbaikan');
            Route::post('/pekerja/insert-proses-perbaikan', [DashboardPekerjaController::class, 'insertProgres'])->name('dashboard.pekerja.insert-proses-perbaikan');
            // ? End For Pekerja
        });

        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::put('/profil/{id}/change', [ProfilController::class, 'update'])->name('profil.update');
        Route::post('/profil/change-email', [ProfilController::class, 'changeEmail'])->name('profil.change-email');

        Route::prefix('dashboard-admin')->group(function () {
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

            Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
            Route::get('/laporan/kendaraan', [LaporanController::class, 'kendaraan'])->name('laporan.kendaraan');
            Route::get('/laporan/perbaikan', [LaporanController::class, 'perbaikan'])->name('laporan.perbaikan');
            Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
            Route::get('/laporan/pekerja', [LaporanController::class, 'pekerja'])->name('laporan.pekerja');
        });
    });
});
