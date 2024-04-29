<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardAdminController extends Controller
{
    public function listPerbaikanBaru()
    {
        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Baru')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-baru.index', compact('perbaikans'));
    }

    public function listPerbaikanAntrian()
    {
        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Antrian')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-antrian.index', compact('perbaikans'));
    }

    public function listPerbaikanDalamProses()
    {
        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Dalam proses')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-dalam-proses.index', compact('perbaikans'));
    }

    public function detailPerbaikanDalamProses(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-dalam-proses.show', compact('perbaikan'));
    }

    public function listPerbaikanSelesaiDiProses()
    {
        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Proses Selesai')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai.index', compact('perbaikans'));
    }

    public function listPerbaikanMenungguBayar()
    {
        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Menunggu Bayar')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.index', compact('perbaikans'));
    }

    public function prosesPerbaikanSelesai(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');

        return view('dashboard.pages.admin.dashboard.proses-perbaikan-selesai.index', compact('perbaikan'));
    }

    public function prosesPerbaikanSelesaiPut(Request $request, Perbaikan $perbaikan)
    {
        // dd($request->all());

        $request->validate([
            'biaya' => ['required', 'string'],
        ], [
            'biaya.required' => 'Biaya tidak boleh kosong',
        ]);

        $perbaikan->update([
            'status' => 'Menunggu Bayar'
        ]);

        $biaya = (int) str_replace(',', '', $request->biaya);

        $pelanggan = $perbaikan->kendaraan->pelanggan;

        $fullName = explode(' ', $pelanggan->nama);
        $firstName = $fullName[0];
        $lastName = isset($fullName[1]) ? $fullName[1] : null;

        Transaksi::updateOrCreate(
            ['order_id' => 'tr-' . $perbaikan->kode_unik],
            [
                'perbaikan_id' => $perbaikan->id,
                'pelanggan_id' => $pelanggan->id,
                'gross_amount' => $biaya,
                'transaction_status' => 'New',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $pelanggan->user->email,
                'phone' => $pelanggan->no_telp,
            ]
        );

        Alert::success('Perbaikan Selesai', 'Perbaikan telah selesai dan transaksi telah dibuat');

        return redirect()->route('dashboard.admin.list-perbaikan-selesai-di-proses');
    }
}
