<?php

namespace App\Http\Controllers;

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
            $recentPerbaikans = Perbaikan::with('kendaraan', 'kendaraan.pelanggan')
                ->where('status', 'Dalam Proses')
                ->latest()
                ->get();

            return view('dashboard.pages.pekerja.index', compact('recentPerbaikans'));
        } else {
            // unauthorized
            abort(403);
        }
    }
}
