<?php

namespace App\Http\Controllers;

use App\Mail\ChangedStatusPerbaikanMail;
use App\Mail\TransaksiCreatedMail;
use App\Mail\TransaksiSelesaiMail;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard Admin';

        $countPerbaikansBaru = Perbaikan::where('status', 'Baru')->count();
        $countPerbaikansAntrian = Perbaikan::where('status', 'Antrian')->count();
        $countPerbaikansProses = Perbaikan::where('status', 'Dalam Proses')->count();
        $countPerbaikansProsesSelesai = Perbaikan::where('status', 'Proses Selesai')->count();
        $countPerbaikansMenungguBayar = Perbaikan::where('status', 'Menunggu Bayar')->count();

        return view('dashboard.pages.admin.index', compact(
            'pageTitle',
            'countPerbaikansBaru',
            'countPerbaikansAntrian',
            'countPerbaikansProses',
            'countPerbaikansProsesSelesai',
            'countPerbaikansMenungguBayar'
        ));
    }

    public function listPerbaikanBaru()
    {
        $pageTitle = 'Perbaikan Baru';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Baru')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-baru.index', compact(
            'perbaikans',
            'pageTitle'
        ));
    }

    public function listPerbaikanAntrian()
    {
        $pageTitle = 'Perbaikan Antrian';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Antrian')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-antrian.index', compact(
            'perbaikans',
            'pageTitle'
        ));
    }

    public function listPerbaikanDalamProses()
    {
        $pageTitle = 'Perbaikan Dalam Proses';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Dalam proses')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-dalam-proses.index', compact(
            'perbaikans',
            'pageTitle'
        ));
    }

    public function detailPerbaikanDalamProses(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan Dalam Proses';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-dalam-proses.show', compact(
            'perbaikan',
            'pageTitle'
        ));
    }

    public function listPerbaikanSelesaiDiProses()
    {
        $pageTitle = 'Perbaikan Selesai Di Proses';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Proses Selesai')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.index', compact(
            'perbaikans',
            'pageTitle'
        ));
    }

    public function detailPerbaikanSelesaiDiProses(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan Selesai Di Proses';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.show', compact(
            'perbaikan',
            'pageTitle'
        ));
    }

    public function listPerbaikanMenungguBayar()
    {
        $pageTitle = 'Perbaikan Menunggu Bayar';

        $perbaikans = Perbaikan::with('kendaraan', 'transaksi')
            ->where('status', 'Menunggu Bayar')
            ->get();

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.index', compact(
            'perbaikans',
            'pageTitle'
        ));
    }

    public function detailPerbaikanMenungguBayar(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan Menunggu Bayar';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.show', compact(
            'perbaikan',
            'pageTitle'
        ));
    }

    public function prosesPerbaikanSelesaiDiProses(Perbaikan $perbaikan)
    {
        $pageTitle = 'Proses Perbaikan Selesai Di Proses';

        $perbaikan->load('kendaraan');

        return view('dashboard.pages.admin.dashboard.perbaikan-selesai-diproses.proses-perbaikan', compact(
            'perbaikan',
            'pageTitle'
        ));
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

        try {
            Mail::to($perbaikan->kendaraan->pelanggan->user->email)->send(new ChangedStatusPerbaikanMail($perbaikan));
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        $pelanggan = $perbaikan->kendaraan->pelanggan;

        $fullName = explode(' ', $pelanggan->nama);
        $firstName = $fullName[0];
        $lastName = null;

        if (count($fullName) > 1) {
            $lastName = end($fullName);
        }

        $transaksi = Transaksi::updateOrCreate(
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

        try {
            Mail::to($transaksi->email)->send(new TransaksiCreatedMail($transaksi));
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        Alert::success('Perbaikan Selesai', 'Perbaikan telah di rubah statusnya dan transaksi telah dibuat');

        return redirect()->route('dashboard.admin.list-perbaikan-selesai-di-proses');
    }

    public function prosesPerbaikanMenungguBayar(Perbaikan $perbaikan)
    {
        $pageTitle = 'Proses Perbaikan Menunggu Bayar';

        $perbaikan->load('kendaraan');
        $perbaikan->load('transaksi');

        return view('dashboard.pages.admin.dashboard.perbaikan-menunggu-bayar.proses-perbaikan', compact(
            'perbaikan',
            'pageTitle'
        ));
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

        try {
            Mail::to($transaksi->email)->send(new ChangedStatusPerbaikanMail($perbaikan));

            Mail::to($transaksi->email)->send(new TransaksiSelesaiMail($transaksi));
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        Alert::success('Perbaikan dan Transaksi Selesai', 'Perbaikan dan transaksi telah selesai');

        return redirect()->route('dashboard.admin.list-perbaikan-menunggu-bayar');
    }
}
