<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Perbaikan;
use App\Models\Settings;
use Illuminate\Http\Request;

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
}
