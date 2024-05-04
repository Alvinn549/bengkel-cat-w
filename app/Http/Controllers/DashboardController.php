<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('dashboard.admin.index');
        } elseif (auth()->user()->role == 'pelanggan') {
            return redirect()->route('dashboard.pelanggan.index');
        } elseif (auth()->user()->role == 'pekerja') {
            return redirect()->route('dashboard.pekerja.index');
        } else {
            abort(403);
        }
    }
}
