<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

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
                'biaya' => ['nullable', 'string'],
                'durasi' => ['nullable', 'string'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'foto.required' => 'Foto tidak boleh kosong',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
                'biaya.required' => 'Biaya tidak boleh kosong',
            ]
        );

        $formatedBiaya = str_replace(',', '', $validate['biaya']);
        $foto = $validate['foto']->store('foto');

        $perbaikan = Perbaikan::create([
            'kendaraan_id' => $request->idKendaraan,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'foto' => $foto,
            'biaya' => (int)$formatedBiaya,
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
                'biaya' => ['nullable', 'string'],
                'status' => ['nullable', 'string'],
                'durasi' => ['nullable', 'string'],
                'tgl_selesai' => ['nullable', 'date'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'biaya.required' => 'Biaya tidak boleh kosong',
                'status.in' => 'Status tidak valid',
                'tgl_selesai.date' => 'Format Tgl. Selesai tidak valid',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $formatedBiaya = str_replace(',', '', $validate['biaya']);

        $perbaikan->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'biaya' => (int)$formatedBiaya,
            'status' => $request->status,
            'durasi' => $request->durasi,
            'tgl_selesai' => $request->tgl_selesai,
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
