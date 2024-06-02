<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pelanggan()
    {
        $durasi = request()->input('durasi');
        $startDate = null;
        $endDate = null;

        if ($durasi) {
            $dates = explode(" to ", $durasi);

            $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
        }

        $pelanggans = Pelanggan::with('kendaraans', 'user')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->latest()
            ->get();

        $totalPelanggans = $pelanggans->count();
        $totalKendaraans = $pelanggans->sum(function ($pelanggan) {
            return $pelanggan->kendaraans->count();
        });

        return view('dashboard.pages.admin.laporan.pelanggan.index', compact('pelanggans', 'totalKendaraans', 'totalPelanggans', 'durasi', 'startDate', 'endDate'));
    }

    public function perbaikan()
    {
        $durasi = request()->input('durasi');
        $status = request()->input('status');
        $startDate = null;
        $endDate = null;

        if ($status != null && $durasi == null) {
            $perbaikans = Perbaikan::where('status', $status)
                ->latest()
                ->get();
        } elseif ($status != null && $durasi != null) {
            $dates = explode(" to ", $durasi);

            $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();

            $perbaikans = Perbaikan::with('kendaraan')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->where('status', $status)
                ->latest()
                ->get();
        } elseif ($status == null && $durasi != null) {
            $dates = explode(" to ", $durasi);

            $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();

            $perbaikans = Perbaikan::with('kendaraan')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->latest()
                ->get();
        } else {
            $perbaikans = Perbaikan::latest()->get();
        }

        $rata_rata_durasi_selesai = null;

        $totalPerbaikans = $perbaikans->count();

        return view('dashboard.pages.admin.laporan.perbaikan.index', compact('perbaikans', 'totalPerbaikans', 'durasi', 'startDate', 'endDate'));
    }

    public function transaksi()
    {
        return view('dashboard.pages.admin.laporan.transaksi.index');
    }

    public function pekerja()
    {
        return view('dashboard.pages.admin.laporan.pekerja.index');
    }
}
