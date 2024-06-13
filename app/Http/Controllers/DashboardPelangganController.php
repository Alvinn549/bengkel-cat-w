<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Midtrans\Config;

class DashboardPelangganController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        $pageTitle = 'Dashboard Pelanggan';

        $pelanggan = Pelanggan::with('kendaraans', 'transaksis')
            ->where('id', auth()->user()->pelanggan->id)
            ->first();

        $kendaraanIds = $pelanggan->kendaraans->pluck('id');

        $perbaikans = Perbaikan::whereIn('kendaraan_id', $kendaraanIds)->get();
        $transaksis = Transaksi::where('pelanggan_id', $pelanggan->id)->get();

        $kendaraanCount = $pelanggan->kendaraans->count();
        $perbaikanInProgressCount = $perbaikans->where('status', '!=', 'Selesai')->count();
        $perbaikanDoneCount = $perbaikans->where('status', 'Selesai')->count();
        $transaksiInProgressCount = $transaksis->where('transaction_status', '!=', 'Selesai')->count();
        $transaksiDoneCount = $transaksis->where('transaction_status', 'Selesai')->count();

        return view('dashboard.pages.pelanggan.index', compact(
            'pageTitle',
            'kendaraanCount',
            'perbaikanInProgressCount',
            'perbaikanDoneCount',
            'transaksiInProgressCount',
            'transaksiDoneCount'
        ));
    }

    public function myKendaraan($idPelanggan)
    {
        $pageTitle = 'Kendaraan Saya';

        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)
            ->latest()
            ->get();

        return view('dashboard.pages.pelanggan.my-kendaraan.index', compact(
            'pageTitle',
            'kendaraans'
        ));
    }

    public function detailMyKendaraan(Kendaraan $kendaraan)
    {
        $pageTitle = 'Detail Kendaraan';

        $kendaraan->load('pelanggan');
        $kendaraan->load('perbaikans');
        $kendaraan->load('tipe');
        $kendaraan->load('merek');

        return view('dashboard.pages.pelanggan.my-kendaraan.show', compact(
            'pageTitle',
            'kendaraan'
        ));
    }

    public function myTransaksi($idPelanggan)
    {
        $pageTitle = 'Transaksi Saya';

        $transaksis = Transaksi::where('pelanggan_id', $idPelanggan)
            ->where('transaction_status', '!=', 'Selesai')
            ->latest()
            ->get();

        return view('dashboard.pages.pelanggan.my-transaksi.index', compact(
            'pageTitle',
            'transaksis'
        ));
    }

    public function detailMyTransaksi(Transaksi $transaksi)
    {
        $pageTitle = 'Detail Transaksi';

        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');
        $midtransClientKey = config('services.midtrans.clientKey');

        return view('dashboard.pages.pelanggan.my-transaksi.show', compact(
            'pageTitle',
            'transaksi',
            'midtransClientKey'
        ));
    }

    public function historyTransaksi($idPelanggan)
    {
        $pageTitle = 'History Transaksi';

        $transaksis = Transaksi::where('pelanggan_id', $idPelanggan)
            ->where('transaction_status', 'Selesai')
            ->latest()
            ->get();

        return view('dashboard.pages.pelanggan.history-transaksi.index', compact(
            'pageTitle',
            'transaksis'
        ));
    }

    public function detailHistoryTransaksi(Transaksi $transaksi)
    {
        $pageTitle = 'Detail History Transaksi';

        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.pelanggan.history-transaksi.show', compact(
            'pageTitle',
            'transaksi'
        ));
    }

    public function currentPerbaikan($idPelanggan)
    {
        $pageTitle = 'Perbaikan Saat Ini';

        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)->get();
        $kendaraanIds = $kendaraans->pluck('id');

        $perbaikans = Perbaikan::with('transaksi')
            ->whereIn('kendaraan_id', $kendaraanIds)
            ->where('status', '!=', 'Selesai')
            ->latest()
            ->get();
        // dd($perbaikans);

        return view('dashboard.pages.pelanggan.current-perbaikan.index', compact(
            'pageTitle',
            'kendaraans',
            'perbaikans'
        ));
    }

    public function detailCurrentPerbaikan(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');
        return view('dashboard.pages.pelanggan.current-perbaikan.show', compact(
            'pageTitle',
            'perbaikan'
        ));
    }

    public function historyPerbaikan($idPelanggan)
    {
        $pageTitle = 'History Perbaikan';

        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)->get();
        $kendaraanIds = $kendaraans->pluck('id');

        $perbaikans = Perbaikan::whereIn('kendaraan_id', $kendaraanIds)
            ->where('status', 'Selesai')
            ->latest()
            ->get();
        // dd($perbaikans);

        return view('dashboard.pages.pelanggan.history-perbaikan.index', compact(
            'pageTitle',
            'kendaraans',
            'perbaikans'
        ));
    }

    public function detailHistoryPerbaikan(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail History Perbaikan';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');
        return view('dashboard.pages.pelanggan.history-perbaikan.show', compact(
            'pageTitle',
            'perbaikan'
        ));
    }

    public function detailHistoryPerbaikanTransaksi(Transaksi $transaksi)
    {
        $pageTitle = 'Detail History Perbaikan Transaksi';

        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.pelanggan.history-perbaikan.detail-transaksi', compact(
            'pageTitle',
            'transaksi'
        ));
    }
}
