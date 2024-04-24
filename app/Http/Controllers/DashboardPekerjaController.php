<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Progres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardPekerjaController extends Controller
{
    public function prosesPerbaikan(Perbaikan $perbaikan)
    {
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

        // dd($latest_progres);

        return view('dashboard.pages.pekerja.proses-perbaikan.index', compact('perbaikan', 'latest_progres'));
    }

    public function storeProgres(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'perbaikan_id' => 'required|exists:perbaikans,id',
                'pekerja_id' => 'required|exists:pekerjas,id',
                'keterangan' => 'nullable|string',
                'foto' => 'required|image|mimes:jpg,png|max:2048',
                'is_selesai' => 'nullable',
            ]);

            $foto = null;
            $is_selesai = null;

            if ($request->has('is_selesai')) {
                if ($request->is_selesai == 'on') {
                    $is_selesai = true;
                } else {
                    $is_selesai = false;
                }
            } else {
                $is_selesai = false;
            }

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto')->store('foto');
            }

            Progres::create([
                'perbaikan_id' => $request->perbaikan_id,
                'pekerja_id' => $request->pekerja_id,
                'keterangan' => $request->keterangan,
                'foto' => $foto,
                'is_selesai' => $is_selesai,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Progress inserted successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error_validation', 'errors' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to insert progress. ' . $e->getMessage()]);
        }
    }

    public function updateProgres(Request $request, Progres $progres)
    {
        // dd($request->all());
        $request->validate([
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,png|max:2048',
            'is_selesai' => 'nullable',
        ]);

        $is_selesai = null;

        if ($request->has('is_selesai')) {
            if ($request->is_selesai == 'on') {
                $is_selesai = true;
            } else {
                $is_selesai = false;
            }
        } else {
            $is_selesai = false;
        }

        // Update progress data
        $progres->update([
            'keterangan' => $request->keterangan,
            'is_selesai' => $is_selesai,
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

        return redirect()->back()->with('success', 'Progress updated successfully');
    }
}
