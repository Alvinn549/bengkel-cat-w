<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pelanggan()
    {
        dd('Laporan Pelanggan');
    }

    public function kendaraan()
    {
        dd('Laporan Kendaraan');
    }

    public function perbaikan()
    {
        dd('Laporan Perbaikan');
    }

    public function transaksi()
    {
        dd('Laporan Transaksi');
    }

    public function pekerja()
    {
        dd('Laporan Pekerja');
    }
}
