<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{

    public function index()
    {
        $pageTittle = 'Pelanggan';

        return view('dashboard.pages.admin.pelanggan.index', compact('pageTittle'));
    }

    public function dataTablePelanggan()
    {
        $pelanggans = Pelanggan::latest()->get();

        return DataTables::of($pelanggans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('dashboard.pages.admin.pelanggan.components.aksi-data-table', ['id' => $data->id]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $pageTittle = 'Tambah Pelanggan';

        return view('dashboard.pages.admin.pelanggan.create', compact('pageTittle'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'nama' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'no_telp' => ['required', 'digits_between:11,16'],
                'jenis_k' => ['required', 'string', 'in:L,P'],
                'alamat' => ['required', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'nama.max' => 'Nama terlalu panjang',
                'email.unique' => 'Email sudah terdaftar',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password terlalu pendek',
                'no_telp.required' => 'Nomor Telepon tidak boleh kosong',
                'no_telp.digits_between' => 'Nomor Telepon tidak valid',
                'jenis_k.required' => 'Jenis Kelamin tidak boleh kosong',
                'jenis_k.in' => 'Jenis Kelamin tidak valid',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $foto = null;

        $user = User::create([
            'role' => 'pelanggan',
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
            'email_verified_at' => now(),
        ]);

        if ($request->hasFile('foto')) {
            $foto = $validate['foto']->store('foto');
        }

        $pelanggan = Pelanggan::create([
            'user_id' => $user->id,
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
            'foto' => $foto,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $pelanggan->nama . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('pelanggan.index');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $pageTittle = 'Edit Pelanggan';

        return view('dashboard.pages.admin.pelanggan.edit', compact(
            'pageTittle',
            'pelanggan'
        ));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'nama' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users,email,' . $pelanggan->user->id],
                'password' => ['nullable', 'string', 'min:8'],
                'no_telp' => ['required', 'digits_between:11,16'],
                'jenis_k' => ['required', 'string', 'in:L,P'],
                'alamat' => ['required', 'string'],
                'foto' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong',
                'nama.max' => 'Nama terlalu panjang',
                'email.unique' => 'Email sudah terdaftar',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password terlalu pendek',
                'no_telp.required' => 'Nomor Telepon tidak boleh kosong',
                'no_telp.digits_between' => 'Nomor Telepon tidak valid',
                'jenis_k.required' => 'Jenis Kelamin tidak boleh kosong',
                'jenis_k.in' => 'Jenis Kelamin tidak valid',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'foto.max' => 'Ukuran gambar terlalu besar',
                'foto.mimes' => 'Format gambar tidak valid',
            ]
        );

        $pelanggan->user->update([
            'email' => $validate['email'],
        ]);

        if ($request->filled('password')) {
            $pelanggan->user->update([
                'password' => bcrypt($validate['password']),
            ]);
        }

        $pelanggan->update([
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
        ]);

        if ($request->hasFile('foto')) {
            if ($pelanggan->foto) {
                // Delete old photo
                Storage::delete($pelanggan->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $pelanggan->update(['foto' => $fotoPath]);
        }

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $pelanggan->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('pelanggan.index');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        try {
            if ($pelanggan->foto) {
                Storage::delete($pelanggan->foto);
            }

            $pelanggan->delete();
            $pelanggan->user->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
