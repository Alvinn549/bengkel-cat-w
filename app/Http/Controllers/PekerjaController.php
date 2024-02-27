<?php

namespace App\Http\Controllers;

use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;


class PekerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pekerjas = Pekerja::latest()->get();
        return view('pages.admin.pekerja.index', compact('pekerjas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.pekerja.create');
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
            'role' => 'pekerja',
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
        ]);

        if ($request->hasFile('foto')) {
            $foto = $validate['foto']->store('foto');
        }

        $pekerja = Pekerja::create([
            'user_id' => $user->id,
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
            'foto' => $foto,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $pekerja->nama . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('pekerja.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pekerja  $pekerja
     * @return \Illuminate\Http\Response
     */
    public function show(Pekerja $pekerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pekerja  $pekerja
     * @return \Illuminate\Http\Response
     */
    public function edit(Pekerja $pekerja)
    {
        return view('pages.admin.pekerja.edit', compact('pekerja'));
    }

    public function update(Request $request, Pekerja $pekerja)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'nama' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users,email,' . $pekerja->user->id],
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

        $pekerja->user->update([
            'email' => $validate['email'],
        ]);

        if ($request->filled('password')) {
            $pekerja->user->update([
                'password' => bcrypt($validate['password']),
            ]);
        }

        $pekerja->update([
            'nama' => $validate['nama'],
            'no_telp' => $validate['no_telp'],
            'jenis_k' => $validate['jenis_k'],
            'alamat' => $validate['alamat'],
        ]);

        if ($request->hasFile('foto')) {
            if ($pekerja->foto) {
                // Delete old photo
                Storage::delete($pekerja->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $pekerja->update(['foto' => $fotoPath]);
        }

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $pekerja->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('pekerja.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pekerja  $pekerja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pekerja $pekerja)
    {
        if (auth()->user()->id == $pekerja->user->id) {
            Alert::error('Error', 'Kamu tidak bisa menghapus akunmu sendiri !');
            return redirect()->route('admin.index');
        }

        if ($pekerja->foto) {
            Storage::delete($pekerja->foto);
        }

        $pekerja->delete();
        $pekerja->user->delete();

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $pekerja->nama . ' berhasil dihapus!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('pekerja.index');
    }
}