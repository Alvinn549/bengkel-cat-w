<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Settings;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        $galleries = Gallery::all();

        return view('landing.index', compact('settings', 'galleries'));
    }
}
