<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;

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

        return redirect()->route('dashboard')
            ->withSuccess('Your email has been verified.');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()
            ->withSuccess('A fresh verification link has been sent to your email address.');
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
            ->with('success', 'Email Berhasil diubah dan link verifikasi telah dikirim');
    }
}
