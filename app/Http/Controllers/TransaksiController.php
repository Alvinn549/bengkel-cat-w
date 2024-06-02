<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class TransaksiController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        return view('dashboard.pages.admin.transaksi.index');
    }

    public function dataTableTransaksi()
    {
        $transaksis = Transaksi::latest()->get();

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

    public function forCashMethod(Request $request)
    {
        $request->validate([
            'chosen_payment' => 'required',
        ], [
            'chosen_payment.required' => 'Pilih metode pembayaran',
        ]);

        $transaksi = Transaksi::find($request->transaksi_id);

        if (!$transaksi) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan'
                ],
                404
            );
        }

        $transaksi->update([
            'chosen_payment' => $request->chosen_payment,
            'pay_by' => $request->chosen_payment,
            'transaction_status' => 'pending',
        ]);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Transaksi berhasil diproses, silahkan tunggu konfirmasi admin',
            ],
            200
        );
    }

    public function forVirtualMethod(Request $request)
    {
        $request->validate([
            'chosen_payment' => 'required',
        ], [
            'chosen_payment.required' => 'Pilih metode pembayaran',
        ]);

        $transaksi = Transaksi::find($request->transaksi_id);

        if (!$transaksi) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan'
                ],
                404
            );
        }

        $transaksi->update([
            'chosen_payment' => $request->chosen_payment,
            'pay_by' => $request->chosen_payment,
            'transaction_status' => 'pending',
        ]);

        $payload = [
            'transaction_details' => [
                'order_id'      => $transaksi->order_id,
                'gross_amount'  => $transaksi->gross_amount,
            ],
            'customer_details' => [
                'first_name'    => $transaksi->first_name,
                'last_name'     => $transaksi->last_name,
                'email'         => $transaksi->email,
                'phone'         => $transaksi->phone,
                'address'       => $transaksi->address,
            ],
            'item_details' => [
                [
                    'id'       => $transaksi->perbaikan->id,
                    'quantity' => 1,
                    'price'    => $transaksi->perbaikan->biaya,
                    'name'     => $transaksi->perbaikan->nama,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage() . ' silahkan coba lagi nanti !'
                ],
                500
            );
        }

        // dd($snapToken);
        $transaksi->update([
            'chosen_payment' => $request->chosen_payment,
            'snap_token' => $snapToken,
        ]);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Transaksi berhasil diproses, silahkan lanjutkan pembayaran',
                'snap_token' => $snapToken
            ],
            200
        );
    }

    public function snapFinish(Request $request)
    {
        // dd($request->all());

        $order_id = $request->get('order_id');
        $status_code = $request->get('status_code');
        $transaction_status = $request->get('transaction_status');

        // dd($order_id, $status_code, $transaction_status);

        $getTransaksi = Transaksi::where('order_id', $order_id)->first();

        if ($transaction_status == 'pending') {
            $getTransaksi->update([
                'transaction_status' => $transaction_status,
            ]);

            Alert::info('Pembayaran', 'Silahkan selesaikan pembayaran anda !');

            return redirect()->route('dashboard.pelanggan.my-transaksi-detail', $getTransaksi->id);
        } elseif ($transaction_status == 'settlement' && $status_code == 200) {
            $getTransaksi->update([
                'transaction_status' => $transaction_status,
            ]);

            $getPerbaikan = $getTransaksi->perbaikan;

            $getPerbaikan->update([
                'status' => 'Selesai',
            ]);

            Alert::success('Pembayaran', 'Pembayaran berhasil dan transaksi telah selesai !');

            return redirect()->route('dashboard.pelanggan.index');
        }
    }

    public function snapUnFinish(Request $request)
    {
        dd($request->all());
    }

    public function snapError(Request $request)
    {
        // dd($request->all());
        $order_id = $request->get('order_id');
        $status_code = $request->get('status_code');
        $transaction_status = $request->get('transaction_status');

        // dd($order_id, $status_code, $transaction_status);

        $getTransaksi = Transaksi::where('order_id', $order_id)->first();

        if ($getTransaksi) {
            if ($transaction_status == 'expire' && $status_code == 407) {
                $getTransaksi->update([
                    'chosen_payment' => null,
                    'pay_by' => null,
                    'snap_token' => null,
                ]);

                Alert::info('Pembayaran', 'Transaksi anda telah expired, silahkan bayar kembali !');

                return redirect()->route('dashboard.pelanggan.my-transaksi-detail', $getTransaksi->id);
            } else {
                $getTransaksi->update([
                    'chosen_payment' => null,
                    'pay_by' => null,
                    'snap_token' => null,
                ]);

                Alert::info('Pembayaran', 'Transaksi anda telah gagal, silahkan bayar kembali !');

                return redirect()->route('dashboard.pelanggan.my-transaksi-detail', $getTransaksi->id);
            }
        }
    }
}
