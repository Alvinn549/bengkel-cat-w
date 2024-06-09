<?php

namespace App\Http\Controllers;

use App\Http\Services\WablasNotification;
use App\Mail\ChangedStatusPerbaikanMail;
use App\Mail\TransaksiSelesaiMail;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Midtrans\Config;
use Midtrans\Snap;

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
        $pageTitle = 'Transaksi';

        return view('dashboard.pages.admin.transaksi.index', compact('pageTitle'));
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

    public function show(Transaksi $transaksi)
    {
        $pageTitle = 'Detail Transaksi';
        $transaksi->load('pelanggan', 'perbaikan', 'perbaikan.kendaraan');

        return view('dashboard.pages.admin.transaksi.show', compact(
            'pageTitle',
            'transaksi'
        ));
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
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaksi->update([
            'chosen_payment' => $request->chosen_payment,
            'pay_by' => $request->chosen_payment,
            'transaction_status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil diproses, silahkan tunggu konfirmasi admin',
        ], 200);
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
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
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
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() . ' silahkan coba lagi nanti !'
            ], 500);
        }

        // dd($snapToken);
        $transaksi->update([
            'chosen_payment' => $request->chosen_payment,
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil diproses, silahkan lanjutkan pembayaran',
            'snap_token' => $snapToken
        ], 200);
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
                'tgl_selesai' => now(),
            ]);

            try {
                $email = $getTransaksi->email;

                Mail::to($email)->send(new ChangedStatusPerbaikanMail($getPerbaikan));
                Mail::to($email)->send(new TransaksiSelesaiMail($getTransaksi));

                Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);

                $this->sendWhatsappNotificationChangedStatus($getPerbaikan);
                $this->sendWhatsappNotificationTransaksiDone($getTransaksi);
            } catch (\Exception $e) {
                Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
            }

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

    public function midtransCallbackNotification(Request $request)
    {
        Log::channel('midtrans')->info('Midtrans Notification Received', $request->all());

        $validated = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
            'status_code' => 'required|integer',
            'va_numbers' => 'array',
            'va_numbers.*.bank' => 'string|nullable',
        ]);

        $order_id = $validated['order_id'];
        $transaction_status = $validated['transaction_status'];
        $status_code = $validated['status_code'];
        $bank = $validated['va_numbers'][0]['bank'] ?? null;

        $getTransaksi = Transaksi::with('perbaikan')->where('order_id', $order_id)->first();

        if ($getTransaksi) {
            if ($transaction_status == 'settlement' && $status_code == 200) {
                $getTransaksi->update([
                    'transaction_status' => $transaction_status,
                    'pay_by' => $bank,
                ]);

                $getPerbaikan = $getTransaksi->perbaikan;

                $getPerbaikan->update([
                    'status' => 'Selesai',
                    'tgl_selesai' => now(),
                ]);

                Log::channel('midtrans')->info('Transaction settled', ['order_id' => $order_id, 'bank' => $bank]);

                try {
                    $email = $getTransaksi->email;

                    Mail::to($email)->send(new ChangedStatusPerbaikanMail($getPerbaikan));
                    Mail::to($email)->send(new TransaksiSelesaiMail($getTransaksi));

                    Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);

                    $this->sendWhatsappNotificationChangedStatus($getPerbaikan);
                    $this->sendWhatsappNotificationTransaksiDone($getTransaksi);
                } catch (\Exception $e) {
                    Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
                }
            } elseif ($transaction_status == 'pending' && $status_code == 201) {
                $getTransaksi->update([
                    'transaction_status' => $transaction_status,
                    'pay_by' => $bank,
                ]);

                Log::channel('midtrans')->info('Transaction pending', ['order_id' => $order_id]);
            } elseif ($transaction_status == 'expire' && $status_code == 202) {
                $getTransaksi->update([
                    'transaction_status' => 'pending',
                    'chosen_payment' => null,
                    'pay_by' => null,
                    'snap_token' => null,
                ]);

                Log::channel('midtrans')->info('Transaction expired', ['order_id' => $order_id]);
            }
        } else {
            Log::channel('midtrans')->warning('Transaction not found for order_id: ' . $order_id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OK',
            'transaksi' => $getTransaksi ?? null,
        ], 200);
    }

    private function sendWhatsappNotificationChangedStatus($perbaikan)
    {
        try {
            $phone = $perbaikan->kendaraan->pelanggan->no_telp;
            $message = $this->createNotificationMessageChangedStatus($perbaikan);

            $wablasNotification = new WablasNotification();
            $wablasNotification->setPhone($phone);
            $wablasNotification->setMessage($message);

            $response = $wablasNotification->sendMessage();

            if ($response['status'] !== 200) {
                Log::channel('wablas')->error('Gagal mengirim notifikasi ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            } else {
                Log::channel('wablas')->info('Notifikasi berhasil dikirim ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('wablas')->error('Gagal mengirim notifikasi: ', ['error' => $e->getMessage()]);
        }
    }

    private function createNotificationMessageChangedStatus($perbaikan)
    {
        $namaPelanggan = $perbaikan->kendaraan->pelanggan->nama;
        $namaPerbaikan = $perbaikan->nama;
        $keteranganPerbaikan = $perbaikan->keterangan;
        $durasiPerbaikan = $perbaikan->durasi;
        $statusPerbaikan = $perbaikan->status;
        $tanggalPerbaikan = $perbaikan->updated_at;

        return "Halo, " . $namaPelanggan . "!\n\n" .
            "Kami ingin menginformasikan bahwa status perbaikan kendaraan Anda telah berubah. Berikut adalah detail perbaikan Anda:\n\n" .
            "*Nama Perbaikan:* " . $namaPerbaikan . "\n" .
            "*Keterangan:* " . $keteranganPerbaikan . "\n" .
            "*Durasi:* " . $durasiPerbaikan . "\n" .
            "*Status:* " . $statusPerbaikan . "\n" .
            "*Tanggal:* " . $tanggalPerbaikan->format('d-m-Y H:i') . "\n\n" .
            "Terima kasih telah mempercayakan layanan kami.\n\n" .
            "Salam,\n" .
            "-Tim Bengkel Cat Wijayanto";
    }

    private function sendWhatsappNotificationTransaksiDone($transaksi)
    {
        try {
            $phone = $transaksi->phone;
            $message = $this->createNotificationMessageTransaksiDone($transaksi);

            $wablasNotification = new WablasNotification();
            $wablasNotification->setPhone($phone);
            $wablasNotification->setMessage($message);

            $response = $wablasNotification->sendMessage();

            if ($response['status'] !== 200) {
                Log::channel('wablas')->error('Gagal mengirim notifikasi ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            } else {
                Log::channel('wablas')->info('Notifikasi berhasil dikirim ', [
                    'status' => $response['status'],
                    'response' => $response['response'],
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('wablas')->error('Gagal mengirim notifikasi: ', ['error' => $e->getMessage()]);
        }
    }

    private function createNotificationMessageTransaksiDone($transaksi)
    {
        $namaPelanggan = $transaksi->pelanggan->nama;
        $orderId = $transaksi->order_id;
        $grossAmount = $transaksi->gross_amount;
        $transaction_status = $transaksi->transaction_status;

        return "Halo, " . $namaPelanggan . "!\n\n" .
            "Kami ingin menginformasikan bahwa transaksi Anda telah selesai. Berikut adalah detail transaksi Anda:\n\n" .
            "*Order ID:* " . $orderId . "\n" .
            "*Jumlah Pembayaran:* Rp " . number_format($grossAmount, 2, ',', '.') . "\n" .
            "*Status Transaksi:* " . $transaction_status . "\n\n" .
            "Terima kasih telah menyelesaikan transaksi ini. Kami menghargai kepercayaan Anda terhadap layanan kami.\n\n" .
            "Salam,\n" .
            "-Tim Bengkel Cat Wijayanto";
    }
}
