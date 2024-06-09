<?php

namespace App\Http\Controllers;

use App\Http\Services\WablasNotification;
use App\Mail\RegisteredCarMail;
use App\Models\Kendaraan;
use App\Models\Merek;
use App\Models\Pelanggan;
use App\Models\Tipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class KendaraanController extends Controller
{

    public function index()
    {
        $pageTitle = 'Kendaraan';

        return view('dashboard.pages.admin.kendaraan.index', compact('pageTitle'));
    }

    public function dataTableKendaraan()
    {
        $kendaraans = Kendaraan::with('pelanggan', 'tipe', 'merek')->latest()->get();

        return DataTables::of($kendaraans)
            ->addIndexColumn()
            ->addColumn('pemilik', function ($data) {
                return $data->pelanggan->nama ?? '-';
            })
            ->addColumn('tipe', function ($data) {
                return $data->tipe->nama_tipe ?? '-';
            })
            ->addColumn('merek', function ($data) {
                return $data->merek->nama_merek ?? '-';
            })
            ->addColumn('aksi', function ($data) {
                return view('dashboard.pages.admin.kendaraan.components.aksi-data-table', ['id' => $data->id]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Tambah Kendaraan';

        $pelanggans = Pelanggan::get(['id', 'nama']);
        $tipes = Tipe::get(['id', 'nama_tipe']);
        $mereks = Merek::get(['id', 'nama_merek']);

        return view('dashboard.pages.admin.kendaraan.create', compact(
            'pageTitle',
            'pelanggans',
            'tipes',
            'mereks'
        ));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'pelanggan_id' => ['required', 'exists:pelanggans,id'],
                'merek_id' => ['required', 'exists:mereks,id'],
                'tipe_id' => ['required', 'exists:tipes,id'],
                'no_plat' => ['required', 'string', 'unique:kendaraans'],
                'keterangan' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'pelanggan_id.required' => 'Pelanggan tidak boleh kosong',
                'pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'no_plat.required' => 'Nomor Plat tidak boleh kosong',
                'no_plat.unique' => 'Nomor Plat sudah terdaftar',
                'merek_id.required' => 'Merek tidak boleh kosong',
                'merek_id.exists' => 'Merek tidak ditemukan',
                'tipe_id.required' => 'Tipe tidak boleh kosong',
                'tipe_id.exists' => 'Tipe tidak ditemukan',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $validate['foto']->store('foto');
        }

        $kendaraan = Kendaraan::create([
            'pelanggan_id' => $validate['pelanggan_id'],
            'tipe_id' => $validate['tipe_id'],
            'merek_id' => $validate['merek_id'],
            'no_plat' => $validate['no_plat'],
            'keterangan' => $validate['keterangan'],
            'foto' => $foto,
        ]);

        try {
            $email = $kendaraan->pelanggan->user->email;

            Mail::to($email)->send(new RegisteredCarMail($kendaraan));

            Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        try {
            $phone = $kendaraan->pelanggan->no_telp;
            $merek = $kendaraan->merek->nama_merek;
            $tipe = $kendaraan->tipe->nama_tipe;
            $noPlat = $kendaraan->no_plat;

            $message = "Halo, " . $kendaraan->pelanggan->nama . "!\n\n" .
                "Kendaraan Anda dengan detail berikut:\n\n" .
                "*Merek:* " . $merek . "\n" .
                "*Tipe:* " . $tipe . "\n" .
                "*Nomor Plat:* " . $noPlat . "\n\n" .
                "Telah berhasil didaftarkan di sistem kami\n" .
                "Terima kasih telah mempercayakan layanan kami.\n\n" .
                "Salam,\n" .
                "-Tim Bengkel Cat Wijayanto";

            $wablasNotification = new WablasNotification();

            $wablasNotification->setPhone($phone);
            $wablasNotification->setMessage($message);

            $response = $wablasNotification->sendMessage();

            if ($response['status'] !== 200) {
                Log::channel('wablas')->error('Gagal mengirim notifikasi ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            } else {
                Log::channel('wablas')->info('Notifikasi berhasil dikirim ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('wablas')->error('Gagal mengirim notifikasi: ', ['error' => $e->getMessage()]);
        }

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $kendaraan->no_plat . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('kendaraan.index');
    }

    public function show(Kendaraan $kendaraan)
    {
        $pageTitle = 'Detail Kendaraan';

        $kendaraan->load('pelanggan');
        $kendaraan->load('perbaikans');
        $kendaraan->load('tipe');
        $kendaraan->load('merek');

        return view('dashboard.pages.admin.kendaraan.show', compact(
            'pageTitle',
            'kendaraan'
        ));
    }

    public function edit(Kendaraan $kendaraan)
    {
        $pageTitle = 'Edit Kendaraan';

        $pelanggans = Pelanggan::get(['id', 'nama']);
        $tipes = Tipe::get(['id', 'nama_tipe']);
        $mereks = Merek::get(['id', 'nama_merek']);

        $kendaraan->load('pelanggan');
        $kendaraan->load('tipe');
        $kendaraan->load('merek');

        return view('dashboard.pages.admin.kendaraan.edit', compact(
            'pageTitle',
            'kendaraan',
            'pelanggans',
            'tipes',
            'mereks'
        ));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'pelanggan_id' => ['required', 'exists:pelanggans,id'],
                'merek_id' => ['required', 'exists:mereks,id'],
                'tipe_id' => ['required', 'exists:tipes,id'],
                'no_plat' => ['required', 'string', 'unique:kendaraans,no_plat,' . $kendaraan->id],
                'keterangan' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'pelanggan_id.required' => 'Pelanggan tidak boleh kosong',
                'pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'no_plat.required' => 'Nomor Plat tidak boleh kosong',
                'no_plat.unique' => 'Nomor Plat sudah terdaftar',
                'merek_id.required' => 'Merek tidak boleh kosong',
                'merek_id.exists' => 'Merek tidak ditemukan',
                'tipe_id.required' => 'Tipe tidak boleh kosong',
                'tipe_id.exists' => 'Tipe tidak ditemukan',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $kendaraan->update([
            'pelanggan_id' => $validate['pelanggan_id'],
            'merek_id' => $validate['merek_id'],
            'tipe_id' => $validate['tipe_id'],
            'no_plat' => $validate['no_plat'],
            'keterangan' => $validate['keterangan'],
        ]);

        if ($request->hasFile('foto')) {
            if ($kendaraan->foto) {
                // Delete old photo
                Storage::delete($kendaraan->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $kendaraan->update(['foto' => $fotoPath]);
        }

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $kendaraan->no_plat . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('kendaraan.index');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        try {
            if ($kendaraan->foto) {
                Storage::delete($kendaraan->foto);
            }

            $kendaraan->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
