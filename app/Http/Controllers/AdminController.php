<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.admin.admin.index');
    }

    public function dataTableAdmin()
    {
        $admins = Admin::get();

        return DataTables::of($admins)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('dashboard.pages.admin.admin.components.aksi-data-table', ['id' => $data->id]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('dashboard.pages.admin.admin.create');
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
            'role' => 'admin',
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
        ]);

        if ($request->hasFile('foto')) {
            $foto = $validate['foto']->store('foto');
        }

        $admin = Admin::create([
            'user_id' => $user->id,
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
            'foto' => $foto,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $admin->nama . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('admin.index');
    }

    public function show(Admin $admin)
    {
        //
    }

    public function edit(Admin $admin)
    {
        return view('dashboard.pages.admin.admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'nama' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users,email,' . $admin->user->id],
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

        $admin->user->update([
            'email' => $validate['email'],
        ]);

        if ($request->filled('password')) {
            $admin->user->update([
                'password' => bcrypt($validate['password']),
            ]);
        }

        $admin->update([
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
        ]);

        if ($request->hasFile('foto')) {
            if ($admin->foto) {
                // Delete old photo
                Storage::delete($admin->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $admin->update(['foto' => $fotoPath]);
        }

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $admin->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('admin.index');
    }

    public function destroy(Admin $admin)
    {
        try {
            if (auth()->user()->id == $admin->user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak bisa menghapus diri sendiri',
                ], 422);
            }

            if ($admin->foto) {
                Storage::delete($admin->foto);
            }

            $admin->delete();
            $admin->user->delete();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
