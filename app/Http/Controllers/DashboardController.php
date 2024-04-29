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
            $perbaikanBerlangsungCount = Perbaikan::where('status', '!=', 'Selesai')->count();
            $transaksiBerlangsungCount = Transaksi::where('transaction_status', '!=', 'Selesai')->count();

            $kendaraanCount = Kendaraan::count();
            $pelangganCount = Pelanggan::count();

            $perbaikanSelesaiCount = Perbaikan::where('status', 'Selesai')->count();
            $transaksiSelesaiCount = Transaksi::where('transaction_status', 'Selesai')->count();

            $countPerbaikansBaru = Perbaikan::where('status', 'Baru')->count();
            $countPerbaikansAntrian = Perbaikan::where('status', 'Antrian')->count();
            $countPerbaikansProses = Perbaikan::where('status', 'Dalam Proses')->count();
            $countPerbaikansProsesSelesai = Perbaikan::where('status', 'Proses Selesai')->count();
            $countPerbaikansMenungguBayar = Perbaikan::where('status', 'Menunggu Bayar')->count();

            return view('dashboard.pages.admin.index', compact(
                'perbaikanBerlangsungCount',
                'transaksiBerlangsungCount',
                'kendaraanCount',
                'pelangganCount',
                'perbaikanSelesaiCount',
                'transaksiSelesaiCount',
                'countPerbaikansBaru',
                'countPerbaikansAntrian',
                'countPerbaikansProses',
                'countPerbaikansProsesSelesai',
                'countPerbaikansMenungguBayar'
            ));
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
            $countPerbaikansBaru = Perbaikan::where('status', 'Baru')->count();
            $countPerbaikansAntrian = Perbaikan::where('status', 'Antrian')->count();
            $countPerbaikansProses = Perbaikan::where('status', 'Dalam Proses')->count();


            return view('dashboard.pages.pekerja.index', compact(
                'countPerbaikansBaru',
                'countPerbaikansAntrian',
                'countPerbaikansProses'
            ));
        } else {
            // unauthorized
            abort(403);
        }
    }
}
