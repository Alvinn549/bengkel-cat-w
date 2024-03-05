<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kendaraan = Kendaraan::find(request('idKendaraan'));

        if (!$kendaraan) {
            Alert::error('Error', 'Kendaraan tidak ditemukan');
            return redirect()->back();
        }

        return view('pages.admin.perbaikan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePerbaikanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $kendaraan = Kendaraan::find($request->idKendaraan);
        if (!$kendaraan) {
            Alert::error('Error', 'Kendaraan tidak ditemukan');
            return redirect()->back();
        }

        $validate = $request->validate(
            [
                'nama' => ['required', 'string'],
                'keterangan' => ['required', 'string'],
                'foto' => ['required', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
                'durasi' => ['required', 'string'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'foto.required' => 'Foto tidak boleh kosong',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
                'durasi.required' => 'Durasi tidak boleh kosong',
            ]
        );

        $foto = $validate['foto']->store('foto');

        $randomString = Str::random(4);
        $randomNumber = rand(1000, 9999);
        $kodeUnik = $randomString . '-' . $randomNumber;

        $perbaikan = Perbaikan::create([
            'kode_unik' => $kodeUnik,
            'kendaraan_id' => $request->idKendaraan,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'foto' => $foto,
            'status' => $request->status,
            'durasi' => $request->durasi,
        ]);

        Alert::toast('<p style="color: white; margin-top: 15px;">' . $perbaikan->nama . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#201658');

        return redirect()->route('kendaraan.show', $request->idKendaraan);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function show(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');
        return view('pages.admin.perbaikan.show', compact('perbaikan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function edit(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');
        return view('pages.admin.perbaikan.edit', compact('perbaikan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePerbaikanRequest  $request
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perbaikan $perbaikan)
    {
        $validate = $request->validate(
            [
                'nama' => ['required', 'string'],
                'keterangan' => ['required', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
                'status' => ['nullable', 'string'],
                'durasi' => ['required', 'string'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'tgl_selesai.date' => 'Format Tgl. Selesai tidak valid',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
                'durasi.required' => 'Durasi tidak boleh kosong',
            ]
        );

        $biaya  = null;
        $tgl_selesai = null;

        if ($validate['status'] == 'Selesai') {
            $request->validate([
                'biaya' => ['required', 'string'],
            ], [
                'biaya.required' => 'Biaya tidak boleh kosong',
            ]);

            $biaya = (int) str_replace(',', '', $request->biaya);

            $pelanggan = $perbaikan->kendaraan->pelanggan;

            $fullName = explode(' ', $pelanggan->nama);
            $firstName = $fullName[0];
            $lastName = isset($fullName[1]) ? $fullName[1] : null;

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
                ]
            );

            $tgl_selesai = now();
        }

        $perbaikan->update([
            'nama' => $validate['nama'],
            'keterangan' => $validate['keterangan'],
            'biaya' => $biaya,
            'status' => $validate['status'],
            'durasi' => $validate['durasi'],
            'tgl_selesai' => $tgl_selesai,
        ]);

        if ($request->hasFile('foto')) {
            if ($perbaikan->foto) {
                // Delete old photo
                Storage::delete($perbaikan->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $perbaikan->update(['foto' => $fotoPath]);
        }

        if ($validate['status'] == 'Selesai') {
            Alert::success('Perbaikan Selesai', 'Perbaikan telah selesai <br> Transaksi dengan nomor <strong>' . $transaksi->order_id . '</strong> telah dibuat. <a href="' . route('transaksi.show', $transaksi) . '">Lihat Transaksi</a>')
                ->toHtml();
        } else {
            Alert::toast('<p style="color: white; margin-top: 10px;">' . $perbaikan->nama . ' berhasil diubah!</p>', 'success')
                ->toHtml()
                ->background('#333A73');
        }

        return redirect()->route('kendaraan.show', $perbaikan->kendaraan_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perbaikan $perbaikan)
    {
        if ($perbaikan->foto) {
            Storage::delete($perbaikan->foto);
        }

        $perbaikan->delete();

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $perbaikan->nama . ' berhasil dihapus!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('kendaraan.show', $perbaikan->kendaraan_id);
    }
}
