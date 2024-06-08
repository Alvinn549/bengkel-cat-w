<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class PerbaikanController extends Controller
{
    public function create()
    {
        $pageTitle = 'Tambah Perbaikan';

        $kendaraan = Kendaraan::find(request('idKendaraan'));

        if (!$kendaraan) {
            Alert::error('Error', 'Kendaraan tidak ditemukan');
            return redirect()->back();
        }

        return view('dashboard.pages.admin.perbaikan.create', compact('pageTitle', 'kendaraan'));
    }

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

    public function show(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan';

        $perbaikan->load('kendaraan');
        $perbaikan->load('progres');

        return view('dashboard.pages.admin.perbaikan.show', compact(
            'pageTitle',
            'perbaikan'
        ));
    }

    public function edit(Perbaikan $perbaikan)
    {
        $pageTitle = 'Edit Perbaikan';
        $perbaikan->load('kendaraan');

        return view('dashboard.pages.admin.perbaikan.edit', compact(
            'pageTitle',
            'perbaikan'
        ));
    }

    public function update(Request $request, Perbaikan $perbaikan)
    {
        $validate = $request->validate(
            [
                'nama' => ['required', 'string'],
                'keterangan' => ['required', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
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

        $perbaikan->update([
            'nama' => $validate['nama'],
            'keterangan' => $validate['keterangan'],
            'durasi' => $validate['durasi'],
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


        Alert::toast('<p style="color: white; margin-top: 10px;">' . $perbaikan->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('kendaraan.show', $perbaikan->kendaraan_id);
    }

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
