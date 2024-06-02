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

        if ($durasi != null) {
            $dates = explode(" to ", $durasi);
            $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
        }

        $perbaikans = Perbaikan::with('kendaraan')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($status != null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->get();

        $totalPerbaikans = $perbaikans->count();

        $average_done_in_days = null;

        $totalDuration = $perbaikans->filter(function ($perbaikan) {
            return $perbaikan->status == 'Selesai' && $perbaikan->tgl_selesai;
        })->sum(function ($perbaikan) {
            return Carbon::parse($perbaikan->created_at)->diffInDays(Carbon::parse($perbaikan->tgl_selesai));
        });

        $completedPerbaikans = $perbaikans->filter(function ($perbaikan) {
            return $perbaikan->status == 'Selesai' && $perbaikan->tgl_selesai;
        })->count();

        if ($completedPerbaikans > 0) {
            $average_done_in_days = $totalDuration / $completedPerbaikans;
        }

        $statuses = ['Baru', 'Antrian', 'Dalam Proses', 'Proses Selesai', 'Menunggu Bayar', 'Selesai'];
        $statusCounts = [];

        foreach ($statuses as $statusName) {
            $statusCounts[$statusName] = Perbaikan::where('status', $statusName)
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->count();
        }

        if ($status != null) {
            $statusCounts = [
                $status => $perbaikans->count()
            ];
        }

        return view('dashboard.pages.admin.laporan.perbaikan.index', compact('perbaikans', 'totalPerbaikans', 'average_done_in_days', 'statusCounts'));
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
