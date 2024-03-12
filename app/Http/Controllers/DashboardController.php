<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return view('dashboard.pages.admin.index');
        } elseif (auth()->user()->role == 'pelanggan') {
            return view('dashboard.pages.pelanggan.index');
        } elseif (auth()->user()->role == 'pekerja') {
            return view('dashboard.pages.pekerja.index');
        } else {
            return redirect('/');
        }
    }
}
