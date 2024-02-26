<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::latest()->get();
        return view('pages.admin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdminRequest  $request
     * @return \Illuminate\Http\Response
     */
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

        Alert::toast($admin->nama . ' berhasil ditambahkan !', 'success');

        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('pages.admin.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdminRequest  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
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

        Alert::toast($admin->nama . ' berhasil diubah !', 'success');

        return redirect()->route('admin.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        if (auth()->user()->id == $admin->user->id) {
            Alert::error('Error', 'Kamu tidak bisa menghapus akunmu sendiri !');
            return redirect()->route('admin.index');
        }

        if ($admin->foto) {
            Storage::delete($admin->foto);
        }

        $admin->delete();
        $admin->user->delete();

        Alert::toast($admin->nama . ' berhasil dihapus !', 'success');

        return redirect()->route('admin.index');
    }
}
