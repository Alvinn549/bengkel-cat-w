<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Perbaikan;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {

        $settings = Settings::first();
        $galleries = Gallery::all();
        $perbaikan = null;

        $pageTitle = $settings->master_nama;

        if ($request->has('kode_unik') && $request->get('kode_unik') != '') {
            $kode_unik = $request->get('kode_unik');
            $perbaikan = Perbaikan::where('kode_unik', 'like', '%' . $kode_unik . '%')->first();
        }

        return view('landing.index', compact(
            'pageTitle',
            'settings',
            'galleries',
            'perbaikan'
        ));
    }

    public function detailPerbaikan(Perbaikan $perbaikan)
    {
        $pageTitle = 'Detail Perbaikan';

        return view('landing.detail-perbaikan', compact(
            'pageTitle',
            'perbaikan'
        ));
    }

    public function sendContactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
        ]);

        $name = $request->name;
        $email = $request->email;
        $pesan = $request->pesan;

        // dd($name, $email, $pesan);

        try {
            Mail::to('alvinn549@gmail.com')->send(new ContactFormMail($name, $email, $pesan));

            Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);
        } catch (\Exception $e) {
            Log::channel('mail')->error('Email gagal dikirim ', ['email' => $email]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan terkirim !'
        ], 200);
    }
}
