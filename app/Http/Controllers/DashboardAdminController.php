<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $countPerbaikansBaru = Perbaikan::where('status', 'Baru')->count();
        $countPerbaikansAntrian = Perbaikan::where('status', 'Antrian')->count();
        $countPerbaikansProses = Perbaikan::where('status', 'Dalam Proses')->count();
        $countPerbaikansProsesSelesai = Perbaikan::where('status', 'Proses Selesai')->count();
        $countPerbaikansMenungguBayar = Perbaikan::where('status', 'Menunggu Bayar')->count();

        $isExclamationMark = false;

        if ($countPerbaikansProsesSelesai > 0 || $countPerbaikansMenungguBayar > 0) {
            $isExclamationMark = true;
        }

        return view('dashboard.pages.admin.index', compact(
            'countPerbaikansBaru',
            'countPerbaikansAntrian',
            'countPerbaikansProses',
            'countPerbaikansProsesSelesai',
            'countPerbaikansMenungguBayar'
        ));
    }

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

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.index', compact('perbaikans'));
    }

    public function detailPerbaikanSelesaiDiProses(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.show', compact('perbaikan'));
    }

    public function listPerbaikanMenungguBayar()
    {
        $perbaikans = Perbaikan::with('kendaraan', 'transaksi')
            ->where('status', 'Menunggu Bayar')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.index', compact('perbaikans'));
    }

    public function detailPerbaikanMenungguBayar(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.show', compact('perbaikan'));
    }

    public function prosesPerbaikanSelesaiDiProses(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.proses-perbaikan', compact('perbaikan'));
    }

    public function prosesPerbaikanSelesaiDiProsesPut(Request $request, Perbaikan $perbaikan)
    {
        // dd($request->all());

        $request->validate([
            'biaya' => ['required', 'string'],
        ], [
            'biaya.required' => 'Biaya tidak boleh kosong',
        ]);


        $biaya = (int) str_replace(',', '', $request->biaya);

        $perbaikan->update([
            'status' => 'Menunggu Bayar',
            'biaya' => $biaya
        ]);

        $pelanggan = $perbaikan->kendaraan->pelanggan;

        $fullName = explode(' ', $pelanggan->nama);
        $firstName = $fullName[0];
        $lastName = null;

        if (count($fullName) > 1) {
            $lastName = end($fullName);
        }

        Transaksi::updateOrCreate(
            ['order_id' => 'tr-' . $perbaikan->kode_unik],
            [
                'perbaikan_id' => $perbaikan->id,
                'pelanggan_id' => $pelanggan->id,
                'gross_amount' => $biaya,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $pelanggan->user->email,
                'phone' => $pelanggan->no_telp,
                'address' => $pelanggan->alamat,
                'transaction_status' => 'pending',
            ]
        );

        Alert::success('Perbaikan Selesai', 'Perbaikan telah di rubah statusnya dan transaksi telah dibuat');

        return redirect()->route('dashboard.admin.list-perbaikan-selesai-di-proses');
    }

    public function prosesPerbaikanMenungguBayar(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('transaksi');

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.proses-perbaikan', compact('perbaikan'));
    }

    public function konfirmasiPembayaranCash(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'konfirmasi_sudah_bayar' => ['required', 'string'],
        ], [
            'konfirmasi_sudah_bayar.required' => 'Konfirmasi sudah bayar tidak boleh kosong',
        ]);

        $transaksi = Transaksi::find($request->transaksi_id);

        $transaksi->update([
            'transaction_status' => 'settlement',
        ]);

        $perbaikan = Perbaikan::find($transaksi->perbaikan_id);

        $perbaikan->update([
            'status' => 'Selesai',
            'tgl_selesai' => now(),
        ]);

        Alert::success('Perbaikan dan Transaksi Selesai', 'Perbaikan dan transaksi telah selesai');

        return redirect()->route('dashboard.admin.list-perbaikan-menunggu-bayar');
    }

    public function prosesPerbaikanMenungguBayarPut(Request $request, Perbaikan $perbaikan)
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

        return redirect()->route('dashboard.admin.list-perbaikan-menunggu-bayar');
    }
}
