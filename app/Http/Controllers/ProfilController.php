<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class ProfilController extends Controller
{
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
}
