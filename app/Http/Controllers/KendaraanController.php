<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class KendaraanController extends Controller
{

    public function index()
    {
        return view('pages.admin.kendaraan.index');
    }

    public function dataTableKendaraan()
    {
        $kendaraans = Kendaraan::with('pelanggan')->get();

        return DataTables::of($kendaraans)
            ->addIndexColumn()
            ->addColumn('pemilik', function ($data) {
                return $data->pelanggan->nama;
            })
            ->addColumn('aksi', function ($data) {
                return view('pages.admin.kendaraan.components.aksi-data-table', ['id' => $data->id]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelanggans = Pelanggan::get(['id', 'nama']);
        return view('pages.admin.kendaraan.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'pelanggan_id' => ['required', 'exists:pelanggans,id'],
                'no_plat' => ['required', 'string'],
                'merek' => ['required', 'string'],
                'tipe' => ['required', 'string'],
                'keterangan' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'pelanggan_id.required' => 'Pelanggan tidak boleh kosong',
                'pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'no_plat.required' => 'Nomor Plat tidak boleh kosong',
                'merek.required' => 'Merek tidak boleh kosong',
                'tipe.required' => 'Tipe tidak boleh kosong',
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
            'no_plat' => $validate['no_plat'],
            'merek' => $validate['merek'],
            'tipe' => $validate['tipe'],
            'keterangan' => $validate['keterangan'],
            'foto' => $foto,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $kendaraan->no_plat . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('kendaraan.index');
    }

    public function show(Kendaraan $kendaraan)
    {
        $kendaraan->load('pelanggan');
        $kendaraan->load('perbaikans');
        return view('pages.admin.kendaraan.show', compact('kendaraan'));
    }

    public function edit(Kendaraan $kendaraan)
    {
        $pelanggans = Pelanggan::get(['id', 'nama']);
        return view('pages.admin.kendaraan.edit', compact('kendaraan', 'pelanggans'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'pelanggan_id' => ['required', 'exists:pelanggans,id'],
                'no_plat' => ['required', 'string'],
                'merek' => ['required', 'string'],
                'tipe' => ['required', 'string'],
                'keterangan' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'pelanggan_id.required' => 'Pelanggan tidak boleh kosong',
                'pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'no_plat.required' => 'Nomor Plat tidak boleh kosong',
                'merek.required' => 'Merek tidak boleh kosong',
                'tipe.required' => 'Tipe tidak boleh kosong',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $kendaraan->update([
            'pelanggan_id' => $validate['pelanggan_id'],
            'no_plat' => $validate['no_plat'],
            'merek' => $validate['merek'],
            'tipe' => $validate['tipe'],
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
