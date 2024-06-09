<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        $pageTitle = 'Login';

        return view('auths.login', compact('pageTitle'));
    }

    public function register()
    {
        $pageTitle = 'Register';

        return view('auths.register', compact('pageTitle'));
    }

    public function doRegister(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8',
            'terms' => 'required'
        ]);

        $user = User::create([
            'role' => 'pelanggan',
            'email' =>  $validate['email'],
            'password' => bcrypt($validate['password']),
        ]);

        Pelanggan::create([
            'user_id' => $user->id,
            'nama' => $validate['nama'],
        ]);

        event(new Registered($user));

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil, silahkan verifikasi Email anda');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
