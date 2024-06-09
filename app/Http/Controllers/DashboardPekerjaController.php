<?php

namespace App\Http\Controllers;

use App\Http\Services\WablasNotification;
use App\Mail\AddedProgresPerbaikanMail;
use App\Mail\ChangedStatusPerbaikanMail;
use App\Models\Perbaikan;
use App\Models\Progres;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DashboardPekerjaController extends Controller
{

    public function index()
    {
        $pageTitle = 'Dashboard Pekerja';

        $countPerbaikansBaru = Perbaikan::where('status', 'Baru')->count();
        $countPerbaikansAntrian = Perbaikan::where('status', 'Antrian')->count();
        $countPerbaikansProses = Perbaikan::where('status', 'Dalam Proses')->count();

        return view('dashboard.pages.pekerja.index', compact(
            'pageTitle',
            'countPerbaikansBaru',
            'countPerbaikansAntrian',
            'countPerbaikansProses'
        ));
    }

    public function listPerbaikanBaru()
    {
        $pageTitle = 'Perbaikan Baru';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Baru')
            ->get();

        return view('dashboard.pages.pekerja.perbaikan-baru.index', compact(
            'pageTitle',
            'perbaikans'
        ));
    }

    public function prosesPerbaikanBaru(Perbaikan $perbaikan)
    {
        $perbaikan->update(['status' => 'Antrian']);

        try {
            $email = $perbaikan->kendaraan->pelanggan->user->email;

            Mail::to($email)->send(new ChangedStatusPerbaikanMail($perbaikan));
            Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);

            $this->sendWhatsappNotificationChangedStatus($perbaikan);
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        Progres::create([
            'perbaikan_id' => $perbaikan->id,
            'pekerja_id' => auth()->user()->pekerja->id,
            'keterangan' => 'Memasukkan perbaikan ke antrian',
            'is_selesai' => false,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $perbaikan->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('dashboard.pekerja.list-perbaikan-baru');
    }

    public function listPerbaikanAntrian()
    {
        $pageTitle = 'Perbaikan Antrian';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Antrian')
            ->get();

        return view('dashboard.pages.pekerja.perbaikan-antrian.index', compact(
            'pageTitle',
            'perbaikans'
        ));
    }

    public function prosesPerbaikanAntrian(Perbaikan $perbaikan)
    {
        $perbaikan->update(['status' => 'Dalam proses']);

        try {
            $email = $perbaikan->kendaraan->pelanggan->user->email;

            Mail::to($email)->send(new ChangedStatusPerbaikanMail($perbaikan));
            Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);

            $this->sendWhatsappNotificationChangedStatus($perbaikan);
        } catch (\Exception $e) {
            Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
        }

        Progres::create([
            'perbaikan_id' => $perbaikan->id,
            'pekerja_id' => auth()->user()->pekerja->id,
            'keterangan' => 'Memproses perbaikan',
            'is_selesai' => false,
        ]);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $perbaikan->nama . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('dashboard.pekerja.list-perbaikan-antrian');
    }

    public function listPerbaikanDalamProses()
    {
        $pageTitle = 'Perbaikan Dalam Proses';

        $perbaikans = Perbaikan::with('kendaraan')
            ->where('status', 'Dalam proses')
            ->get();

        return view('dashboard.pages.pekerja.perbaikan-dalam-proses.index', compact(
            'pageTitle',
            'perbaikans'
        ));
    }

    public function prosesPerbaikanDalamProses(Perbaikan $perbaikan)
    {
        $pageTitle = 'Proses Perbaikan';

        $perbaikan->load('kendaraan');
        $perbaikan->load('kendaraan.pelanggan');
        $perbaikan->load('kendaraan.tipe');
        $perbaikan->load('kendaraan.merek');
        $perbaikan->load('progres');
        $perbaikan->load('progres.pekerja');

        $latest_progres = [
            'id' => $perbaikan->progres()->orderByDesc('id')->first()->id ?? null,
            'is_selesai' => $perbaikan->progres()->orderByDesc('id')->first()->is_selesai ?? null,
        ];

        return view('dashboard.pages.pekerja.proses-perbaikan.index', compact(
            'pageTitle',
            'perbaikan',
            'latest_progres'
        ));
    }

    public function storeProgres(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'perbaikan_id' => 'required|exists:perbaikans,id',
                'pekerja_id' => 'required|exists:pekerjas,id',
                'keterangan' => 'required|string',
                'foto' => 'nullable|image|mimes:jpg,png|max:5048',
                'is_selesai' => 'nullable',
            ]);

            $foto = null;

            if ($request->has('is_selesai')) {
                if ($request->is_selesai == 'on') {

                    $perbaikan = Perbaikan::find($request->perbaikan_id);
                    $perbaikan->update(['status' => 'Proses Selesai']);

                    try {
                        $email = $perbaikan->kendaraan->pelanggan->user->email;

                        Mail::to($email)->send(new ChangedStatusPerbaikanMail($perbaikan));
                        Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);

                        $this->sendWhatsappNotificationChangedStatus($perbaikan);
                    } catch (\Exception $e) {
                        Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
                    }

                    if ($request->hasFile('foto')) {
                        $foto = $request->file('foto')->store('foto');
                    }

                    $progres = Progres::create([
                        'perbaikan_id' => $request->perbaikan_id,
                        'pekerja_id' => $request->pekerja_id,
                        'keterangan' => $request->keterangan,
                        'foto' => $foto,
                        'is_selesai' => true,
                    ]);

                    try {
                        $email = $perbaikan->kendaraan->pelanggan->user->email;

                        Mail::to($email)->send(new AddedProgresPerbaikanMail($progres));
                        Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);
                    } catch (\Exception $e) {
                        Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
                    }

                    return response()->json([
                        'status' => 'success-update-to-selesai',
                        'message' => 'Progress inserted successfully and updated to selesai'
                    ]);
                }
            } else {

                if ($request->hasFile('foto')) {
                    $foto = $request->file('foto')->store('foto');
                }

                $progres = Progres::create([
                    'perbaikan_id' => $request->perbaikan_id,
                    'pekerja_id' => $request->pekerja_id,
                    'keterangan' => $request->keterangan,
                    'foto' => $foto,
                    'is_selesai' => false,
                ]);

                try {
                    $email = $progres->perbaikan->kendaraan->pelanggan->user->email;

                    Mail::to($email)->send(new AddedProgresPerbaikanMail($progres));
                    Log::channel('mail')->info('Email berhasil dikirim ', ['email' => $email]);
                } catch (\Exception $e) {
                    Log::channel('mail')->error('Gagal mengirim email: ', ['error' => $e->getMessage()]);
                }

                return response()->json([
                    'status' => 'success-insert-new-progress',
                    'message' => 'Progress inserted successfully'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error_validation',
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to insert progress. ' . $e->getMessage()
            ]);
        }
    }

    public function updateProgres(Request $request, Progres $progres)
    {
        // dd($request->all());
        $request->validate([
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,png|max:5000',
            'is_selesai' => 'nullable',
        ]);

        $progres->update([
            'keterangan' => $request->keterangan,
        ]);

        if ($request->hasFile('foto')) {
            if ($progres->foto) {
                // Delete old photo
                Storage::delete($progres->foto);
            }

            // Store new photo
            $fotoPath = $request->file('foto')->store('foto');
            $progres->update(['foto' => $fotoPath]);
        }

        return redirect()->back()
            ->with('success', 'Progress updated successfully');
    }

    private function sendWhatsappNotificationChangedStatus($perbaikan)
    {
        try {
            $phone = $this->getPhoneNumber($perbaikan);
            $message = $this->createNotificationMessage($perbaikan);

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

    private function getPhoneNumber($perbaikan)
    {
        return $perbaikan->kendaraan->pelanggan->no_telp;
    }

    private function createNotificationMessage($perbaikan)
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
}
