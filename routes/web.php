<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
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

Route::get('/', [LandingPageController::class, 'index'])
    ->name('home');

Route::get('/detail-perbaikan/{perbaikan}', [LandingPageController::class, 'detailPerbaikan'])
    ->name('home.detail-perbaikan');

Route::post('/send-contact-form', [LandingPageController::class, 'sendContactForm'])
    ->name('send.contact.form');

// Redirect Url For Snap Midtrans 
Route::get('/snap/finish', [TransaksiController::class, 'snapFinish'])
    ->name('snap.finish');

Route::get('/snap/un-finish', [TransaksiController::class, 'snapUnFinish'])
    ->name('snap.un-finish');

Route::get('/snap/error', [TransaksiController::class, 'snapError'])
    ->name('snap.error');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/do-login', [AuthController::class, 'doLogin'])
        ->name('do-login');

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/do-register', [AuthController::class, 'doRegister'])
        ->name('doRegister');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::post('/email/change-email', [VerificationController::class, 'changeEmail'])
        ->name('verification.change-email');

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/profil', [ProfilController::class, 'index'])
            ->name('profil.index');

        Route::put('/profil/{id}/change', [ProfilController::class, 'update'])
            ->name('profil.update');

        Route::post('/profil/change-email', [ProfilController::class, 'changeEmail'])
            ->name('profil.change-email');

        Route::prefix('dashboard-pelanggan')->group(function () {
            Route::get('/index', [DashboardPelangganController::class, 'index'])
                ->name('dashboard.pelanggan.index');

            Route::get('/my-kendaraan/{idPelanggan}', [DashboardPelangganController::class, 'myKendaraan'])
                ->name('dashboard.pelanggan.my-kendaraan');

            Route::get('/my-kendaraan/detail/{kendaraan}', [DashboardPelangganController::class, 'detailMyKendaraan'])
                ->name('dashboard.pelanggan.my-kendaraan-detail');

            Route::get('/my-transaksi/{idPelanggan}', [DashboardPelangganController::class, 'myTransaksi'])
                ->name('dashboard.pelanggan.my-transaksi');

            Route::get('/my-transaksi/detail/{transaksi}', [DashboardPelangganController::class, 'detailMyTransaksi'])
                ->name('dashboard.pelanggan.my-transaksi-detail');

            Route::post('/my-transaksi/proses/cash', [TransaksiController::class, 'forCashMethod'])
                ->name('dashboard.pelanggan.proses-my-transaksi-cash');

            Route::post('/my-transaksi/proses/virtual', [TransaksiController::class, 'forVirtualMethod'])
                ->name('dashboard.pelanggan.proses-my-transaksi-virtual');

            Route::get('/history-transaksi/{idPelanggan}', [DashboardPelangganController::class, 'historyTransaksi'])
                ->name('dashboard.pelanggan.history-transaksi');

            Route::get('/history-transaksi/detail/{transaksi}', [DashboardPelangganController::class, 'detailHistoryTransaksi'])
                ->name('dashboard.pelanggan.history-transaksi-detail');

            Route::get('/perbaikan-saat-ini/{idPelanggan}', [DashboardPelangganController::class, 'currentPerbaikan'])
                ->name('dashboard.pelanggan.current-perbaikan');

            Route::get('/perbaikan-saat-ini/detail/{perbaikan}', [DashboardPelangganController::class, 'detailCurrentPerbaikan'])
                ->name('dashboard.pelanggan.current-perbaikan-detail');

            Route::get('/history-perbaikan/{idPelanggan}', [DashboardPelangganController::class, 'historyPerbaikan'])
                ->name('dashboard.pelanggan.history-perbaikan');

            Route::get('/history-perbaikan/detail/{perbaikan}', [DashboardPelangganController::class, 'detailHistoryPerbaikan'])
                ->name('dashboard.pelanggan.history-perbaikan-detail');

            Route::get('/history-perbaikan/detail-transaksi/{transaksi}', [DashboardPelangganController::class, 'detailHistoryPerbaikanTransaksi'])
                ->name('dashboard.pelanggan.history-perbaikan-detail-transaksi');
        });

        Route::prefix('dashboard-pekerja')->group(function () {
            Route::get('/index', [DashboardPekerjaController::class, 'index'])
                ->name('dashboard.pekerja.index');

            Route::get('/list-perbaikan-baru', [DashboardPekerjaController::class, 'listPerbaikanBaru'])
                ->name('dashboard.pekerja.list-perbaikan-baru');

            Route::get('/proses-perbaikan-baru/{perbaikan}', [DashboardPekerjaController::class, 'prosesPerbaikanBaru'])
                ->name('dashboard.pekerja.proses-perbaikan-baru');

            Route::get('/list-perbaikan-antrian', [DashboardPekerjaController::class, 'listPerbaikanAntrian'])
                ->name('dashboard.pekerja.list-perbaikan-antrian');

            Route::get('/proses-perbaikan-antrian/{perbaikan}', [DashboardPekerjaController::class, 'prosesPerbaikanAntrian'])
                ->name('dashboard.pekerja.proses-perbaikan-antrian');

            Route::get('/list-perbaikan-dalam-proses', [DashboardPekerjaController::class, 'listPerbaikanDalamProses'])
                ->name('dashboard.pekerja.list-perbaikan-dalam-proses');

            Route::get('/proses-perbaikan-dalam-proses/{perbaikan}', [DashboardPekerjaController::class, 'prosesPerbaikanDalamProses'])
                ->name('dashboard.pekerja.proses-perbaikan-dalam-proses');

            Route::post('/store-proses-perbaikan', [DashboardPekerjaController::class, 'storeProgres'])
                ->name('dashboard.pekerja.store-proses-perbaikan');

            Route::put('/update-proses-perbaikan/{progres}', [DashboardPekerjaController::class, 'updateProgres'])
                ->name('dashboard.pekerja.update-proses-perbaikan');
        });

        Route::prefix('dashboard-admin')->group(function () {
            Route::get('/index', [DashboardAdminController::class, 'index'])
                ->name('dashboard.admin.index');

            Route::get('/list-perbaikan-baru', [DashboardAdminController::class, 'listPerbaikanBaru'])
                ->name('dashboard.admin.list-perbaikan-baru');

            Route::get('/list-perbaikan-antrian', [DashboardAdminController::class, 'listPerbaikanAntrian'])
                ->name('dashboard.admin.list-perbaikan-antrian');

            Route::get('/list-perbaikan-dalam-proses', [DashboardAdminController::class, 'listPerbaikanDalamProses'])
                ->name('dashboard.admin.list-perbaikan-dalam-proses');

            Route::get('/list-perbaikan-selesai-di-proses', [DashboardAdminController::class, 'listPerbaikanSelesaiDiProses'])
                ->name('dashboard.admin.list-perbaikan-selesai-di-proses');

            Route::get('/list-perbaikan-menunggu-bayar', [DashboardAdminController::class, 'listPerbaikanMenungguBayar'])
                ->name('dashboard.admin.list-perbaikan-menunggu-bayar');

            Route::get('/detail-perbaikan-menunggu-bayar/{perbaikan}', [DashboardAdminController::class, 'detailPerbaikanMenungguBayar'])
                ->name('dashboard.admin.detail-perbaikan-menunggu-bayar');

            Route::get('/detail-perbaikan-dalam-proses/{perbaikan}', [DashboardAdminController::class, 'detailPerbaikanDalamProses'])
                ->name('dashboard.admin.detail-perbaikan-dalam-proses');

            Route::get('/detail-perbaikan-selesai-di-proses/{perbaikan}', [DashboardAdminController::class, 'detailPerbaikanSelesaiDiProses'])
                ->name('dashboard.admin.detail-perbaikan-selesai-di-proses');

            Route::get('/proses-perbaikan-selesai-di-proses/{perbaikan}', [DashboardAdminController::class, 'prosesPerbaikanSelesaiDiProses'])
                ->name('dashboard.admin.proses-perbaikan-selesai');

            Route::put('/proses-perbaikan-selesai-di-proses/{perbaikan}/put', [DashboardAdminController::class, 'prosesPerbaikanSelesaiDiProsesPut'])
                ->name('dashboard.admin.proses-perbaikan-selesai-put');

            Route::get('/proses-perbaikan-menunggu-bayar/{perbaikan}', [DashboardAdminController::class, 'prosesPerbaikanMenungguBayar'])
                ->name('dashboard.admin.proses-menunggu-bayar');

            Route::put('/proses-perbaikan-menunggu-bayar/{perbaikan}/put', [DashboardAdminController::class, 'prosesPerbaikanMenungguBayarPut'])
                ->name('dashboard.admin.proses-menunggu-bayar-put');

            Route::post('/konfirmasi-pembayaran-cash', [DashboardAdminController::class, 'konfirmasiPembayaranCash'])
                ->name('dashboard.admin.konfirmasi-pembayaran-cash');

            Route::get('/admin-data-table', [AdminController::class, 'dataTableAdmin'])
                ->name('admin.data-table');

            Route::resource('admin', AdminController::class);

            Route::get('/pekerja-data-table', [PekerjaController::class, 'dataTablePekerja'])
                ->name('pekerja.data-table');

            Route::resource('pekerja', PekerjaController::class);

            Route::get('/pelanggan-data-table', [PelangganController::class, 'dataTablePelanggan'])
                ->name('pelanggan.data-table');

            Route::resource('pelanggan', PelangganController::class);

            Route::get('/kendaraan-data-table', [KendaraanController::class, 'dataTableKendaraan'])
                ->name('kendaraan.data-table');

            Route::resource('kendaraan', KendaraanController::class);
            Route::resource('perbaikan', PerbaikanController::class);
            Route::resource('tipe', TipeController::class);
            Route::resource('merek', MerekController::class);

            Route::get('/transaksi-data-table', [TransaksiController::class, 'dataTableTransaksi'])
                ->name('transaksi.data-table');

            Route::resource('transaksi', TransaksiController::class);

            Route::resource('settings', SettingsController::class);

            Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])
                ->name('laporan.pelanggan');

            Route::get('/laporan/perbaikan', [LaporanController::class, 'perbaikan'])
                ->name('laporan.perbaikan');

            Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi'])
                ->name('laporan.transaksi');
        });
    });
});
