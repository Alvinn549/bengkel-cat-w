<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('login') : view('emails.verify-notice');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        try {
            Mail::to($request->user())->send(new WelcomeMail($request->user()->pelanggan->nama));
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        return redirect()->route('dashboard')
            ->withSuccess('Email berhasil diverifikasi');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()
            ->withSuccess('Link verifikasi yang baru telah dikirim');
    }

    public function changeEmail(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users,email,' . auth()->user()->id],
        ]);

        $user = User::find(auth()->user()->id);

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Email sudah terdaftar');
        }

        $user->update([
            'email' => $validate['email']
        ]);

        event(new Registered($user));

        return redirect()->route('verification.notice')
            ->with('success', 'Email Berhasil diubah dan link verifikasi yang baru telah dikirim');
    }
}
