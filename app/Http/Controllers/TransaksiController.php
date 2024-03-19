<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.admin.transaksi.index');
    }

    public function dataTableTransaksi()
    {
        $transaksis = Transaksi::where('transaction_status', 'Selesai')->get();

        return DataTables::of($transaksis)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('dashboard.pages.admin.transaksi.components.aksi-data-table', ['id' => $data->id]);
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(StoreTransaksiRequest $request)
    {
        //
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.admin.transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        //
    }

    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        //
    }

    public function destroy(Transaksi $transaksi)
    {
        try {
            $transaksi->delete();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
