<?php

namespace App\Http\Controllers;

use App\Models\Pekerja;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Auth\Events\Registered;

class ProfilController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return view('dashboard.pages.admin.profil.index');
        } elseif (auth()->user()->role == 'pekerja') {
            return view('dashboard.pages.pekerja.profil.index');
        } elseif (auth()->user()->role == 'pelanggan') {
            return view('dashboard.pages.pelanggan.profil.index');
        }
    }
    public function changeEmail(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users,email,' . auth()->user()->id],
        ]);

        $user = User::find(auth()->user()->id);

        if (!$user) {
            return redirect()->back()->with('error', 'Email sudah terdaftar');
        }

        $user->update([
            'email' => $validate['email']
        ]);

        event(new Registered($user));

        return redirect()->route('verification.notice')->with('success', 'Email Berhasil diubah dan link verifikasi telah dikirim');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if ($user->role == 'admin') {
            $admin = Pelanggan::where('user_id', $user->id)->first();

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

            return redirect()->route('dashboard');
        } elseif ($user->role == 'pekerja') {
            $pekerja = Pekerja::where('user_id', $user->id)->first();

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

            return redirect()->route('dashboard');
        } elseif ($user->role == 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();

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

            Alert::toast('<p style="color: white; margin-top: 10px;">Profil berhasil diubah!</p>', 'success')
                ->toHtml()
                ->background('#333A73');

            return redirect()->route('profil.index');
        }
    }
}
