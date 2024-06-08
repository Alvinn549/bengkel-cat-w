<?php

namespace App\Http\Controllers;

use App\Models\Admin;
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
        $pageTitle = 'Profil';

        if (auth()->user()->role == 'admin') {
            return view('dashboard.pages.admin.profil.index', compact('pageTitle'));
        } elseif (auth()->user()->role == 'pekerja') {
            return view('dashboard.pages.pekerja.profil.index', compact('pageTitle'));
        } elseif (auth()->user()->role == 'pelanggan') {
            return view('dashboard.pages.pelanggan.profil.index', compact('pageTitle'));
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
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $validatedData = $this->validateRequest($request, $user->id);

        $this->updateUser($user, $validatedData);

        if ($user->role == 'admin') {
            $admin = Admin::where('user_id', $user->id)->first();
            $admin->update([
                'nama' => $validatedData['nama'],
                'no_telp' => $validatedData['no_telp'],
                'jenis_k' => $validatedData['jenis_k'],
                'alamat' => $validatedData['alamat'],
            ]);

            $this->updatePhoto($admin, $request);
            Alert::toast('<p style="color: white; margin-top: 10px;">' . $admin->nama . ' berhasil diubah!</p>', 'success')
                ->toHtml()
                ->background('#333A73');
        } elseif ($user->role == 'pekerja') {
            $pekerja = Pekerja::where('user_id', $user->id)->first();
            $pekerja->update([
                'nama' => $validatedData['nama'],
                'no_telp' => $validatedData['no_telp'],
                'jenis_k' => $validatedData['jenis_k'],
                'alamat' => $validatedData['alamat'],
            ]);

            $this->updatePhoto($pekerja, $request);
            Alert::toast('<p style="color: white; margin-top: 10px;">' . $pekerja->nama . ' berhasil diubah!</p>', 'success')
                ->toHtml()
                ->background('#333A73');
        } elseif ($user->role == 'pelanggan') {
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();
            $pelanggan->update([
                'nama' => $validatedData['nama'],
                'no_telp' => $validatedData['no_telp'],
                'jenis_k' => $validatedData['jenis_k'],
                'alamat' => $validatedData['alamat'],
            ]);

            $this->updatePhoto($pelanggan, $request);
            Alert::toast('<p style="color: white; margin-top: 10px;">Profil berhasil diubah!</p>', 'success')
                ->toHtml()
                ->background('#333A73');
        }

        return redirect()->route('profil.index');
    }

    private function updateUser($user, $validatedData)
    {
        $user->update([
            'email' => $validatedData['email'],
        ]);

        if (array_key_exists('password', $validatedData) && $validatedData['password']) {
            $user->update([
                'password' => bcrypt($validatedData['password']),
            ]);
        }
    }

    private function validateRequest($request, $userId)
    {
        return $request->validate(
            [
                'nama' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users,email,' . $userId],
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
    }

    private function updatePhoto($entity, $request)
    {
        if ($request->hasFile('foto')) {
            if ($entity->foto) {
                // Delete old photo
                Storage::delete($entity->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $entity->update(['foto' => $fotoPath]);
        }
    }
}
