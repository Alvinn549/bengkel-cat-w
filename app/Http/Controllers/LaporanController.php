<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function pelanggan()
    {
        $pageTitle = 'Laporan Pelanggan';

        $durasi = request()->get('durasi');

        [$startDate, $endDate] = $this->parseDateRange($durasi);

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

        return view('dashboard.pages.admin.laporan.pelanggan.index', compact('pageTitle', 'pelanggans', 'totalKendaraans', 'totalPelanggans', 'durasi', 'startDate', 'endDate'));
    }

    public function perbaikan()
    {
        $pageTitle = 'Laporan Perbaikan Kendaraan';

        $durasi = request()->get('durasi');
        $status = request()->get('status');

        [$startDate, $endDate] = $this->parseDateRange($durasi);

        $perbaikans = Perbaikan::with('kendaraan', 'transaksi', 'kendaraan.pelanggan')
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

        return view('dashboard.pages.admin.laporan.perbaikan.index', compact('pageTitle', 'perbaikans', 'totalPerbaikans', 'average_done_in_days', 'statusCounts'));
    }

    public function transaksi()
    {
        $pageTitle = 'Laporan Transaksi';

        $durasi = request()->get('durasi');
        $status = request()->get('status');
        $pelanggan = request()->get('pelanggan');

        [$startDate, $endDate] = $this->parseDateRange($durasi);

        $transaksis = Transaksi::with('perbaikan', 'perbaikan.kendaraan', 'perbaikan.kendaraan.pelanggan')
            ->when($durasi, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('transaction_status', $status);
            })
            ->when($pelanggan, function ($query) use ($pelanggan) {
                return $query->where('pelanggan_id', $pelanggan);
            })
            ->latest()
            ->get();

        $totalIncome = $transaksis->filter(function ($transaksi) {
            return $transaksi->transaction_status == 'settlement';
        })->sum('gross_amount');

        $potentialIncome = $transaksis->filter(function ($transaksi) {
            return $transaksi->transaction_status != 'settlement';
        })->sum('gross_amount');

        $pelanggans = Pelanggan::with('kendaraans', 'user')->get();

        // dd($totalTransaksiSelesai);

        return view('dashboard.pages.admin.laporan.transaksi.index', compact('pageTitle', 'transaksis', 'totalIncome', 'potentialIncome', 'pelanggans'));
    }

    private function parseDateRange($durasi)
    {
        $startDate = null;
        $endDate = null;

        if ($durasi) {
            $dates = explode(" to ", $durasi);

            if (count($dates) == 1) {
                $dates[1] = $dates[0];
            }

            if (isset($dates[0]) && isset($dates[1])) {
                $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
            }
        }

        return [$startDate, $endDate];
    }
}
