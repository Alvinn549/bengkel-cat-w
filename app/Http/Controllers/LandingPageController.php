<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Perbaikan;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $settings = Settings::first();
        $galleries = Gallery::all();
        $perbaikan = null;

        if ($request->has('kode_unik') && $request->get('kode_unik') != '') {
            $kode_unik = $request->get('kode_unik');
            $perbaikan = Perbaikan::where('kode_unik', 'like', '%' . $kode_unik . '%')->first();
        }

        return view('landing.index', compact('settings', 'galleries', 'perbaikan'));
    }

    public function detailPerbaikan(Perbaikan $perbaikan)
    {
        return view('landing.detail-perbaikan', compact('perbaikan'));
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
        } catch (\Exception $e) {
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
