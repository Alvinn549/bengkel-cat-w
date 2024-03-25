<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return view('dashboard.pages.admin.index');
        } elseif (auth()->user()->role == 'pelanggan') {
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
                'kendaraanCount',
                'perbaikanInProgressCount',
                'perbaikanDoneCount',
                'transaksiInProgressCount',
                'transaksiDoneCount'
            ));
        } elseif (auth()->user()->role == 'pekerja') {
            return view('dashboard.pages.pekerja.index');
        } else {
            return redirect('/');
        }
    }

    public function myKendaraan($idPelanggan)
    {
        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)->get();

        return view('dashboard.pages.pelanggan.my-kendaraan.index', compact('kendaraans'));
    }

    public function detailMyKendaraan(Kendaraan $kendaraan)
    {
        $kendaraan->load('pelanggan');
        $kendaraan->load('perbaikans');
        $kendaraan->load('tipe');
        $kendaraan->load('merek');

        return view('dashboard.pages.pelanggan.my-kendaraan.show', compact('kendaraan'));
    }

    public function myTransaksi($idPelanggan)
    {
        $transaksis = Transaksi::where('pelanggan_id', $idPelanggan)
            ->where('transaction_status', '!=', 'Selesai')
            ->latest()
            ->get();

        return view('dashboard.pages.pelanggan.my-transaksi.index', compact('transaksis'));
    }

    public function detailMyTransaksi(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.pelanggan.my-transaksi.show', compact('transaksi'));
    }

    public function historyTransaksi($idPelanggan)
    {
        $transaksis = Transaksi::where('pelanggan_id', $idPelanggan)
            ->where('transaction_status', 'Selesai')
            ->latest()
            ->get();

        return view('dashboard.pages.pelanggan.history-transaksi.index', compact('transaksis'));
    }

    public function detailHistoryTransaksi(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.pelanggan.history-transaksi.show', compact('transaksi'));
    }

    public function currentPerbaikan($idPelanggan)
    {
        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)->get();
        $kendaraanIds = $kendaraans->pluck('id');

        $perbaikans = Perbaikan::whereIn('kendaraan_id', $kendaraanIds)
            ->where('status', '!=', 'Selesai')
            ->get();
        // dd($perbaikans);

        return view('dashboard.pages.pelanggan.current-perbaikan.index', compact('kendaraans', 'perbaikans'));
    }

    public function detailCurrentPerbaikan(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');
        return view('dashboard.pages.pelanggan.current-perbaikan.show', compact('perbaikan'));
    }

    public function historyPerbaikan($idPelanggan)
    {
        $kendaraans = Kendaraan::where('pelanggan_id', $idPelanggan)->get();
        $kendaraanIds = $kendaraans->pluck('id');

        $perbaikans = Perbaikan::whereIn('kendaraan_id', $kendaraanIds)
            ->where('status', 'Selesai')
            ->get();
        // dd($perbaikans);

        return view('dashboard.pages.pelanggan.history-perbaikan.index', compact('kendaraans', 'perbaikans'));
    }

    public function detailHistoryPerbaikan(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');
        return view('dashboard.pages.pelanggan.history-perbaikan.show', compact('perbaikan'));
    }
}
