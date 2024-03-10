<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SettingsController extends Controller
{

    public function index()
    {
        $settings = Settings::first();
        $galleries = Gallery::all();

        return view('dashboard.pages.admin.settings.index', compact('settings', 'galleries'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate(
            [
                'master_nama' => ['required', 'string'],
                'deskripsi' => ['required', 'string'],
                'alamat' => ['required', 'string'],
                'jam_operasional1' => ['required', 'string'],
                'jam_operasional2' => ['required', 'string'],
                'hero' => ['nullable', 'image', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5000'],
                'telepon' => ['nullable', 'string'],
                'email' => ['nullable', 'string', 'email'],
                'facebook' => ['nullable', 'url'],
                'instagram' => ['nullable', 'url'],
                'whatsapp' => ['nullable', 'string'],
            ],
            [
                'master_nama.required' => 'Nama tidak boleh kosong',
                'deskripsi.required' => 'Deskripsi tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'jam_operasional1.required' => 'Jam Operasional tidak boleh kosong',
                'jam_operasional2.required' => 'Jam Operasional tidak boleh kosong',
                'hero.max' => 'Ukuran gambar terlalu besar',
                'hero.mimes' => 'Format gambar tidak valid',
                'email.email' => 'Email tidak valid',
                'facebook.url' => 'Url Facebook tidak valid',
                'instagram.url' => 'Url Instagram tidak valid',
            ]
        );

        $settings = Settings::first();

        $jam_operasional = $validate['jam_operasional1'] . ' to ' . $validate['jam_operasional2'];

        $settings->update([
            'master_nama' => $validate['master_nama'],
            'deskripsi' => $validate['deskripsi'],
            'alamat' => $validate['alamat'],
            'jam_operasional' => $jam_operasional,
            'telepon' => $validate['telepon'],
            'email' => $validate['email'],
            'facebook' => $validate['facebook'],
            'instagram' => $validate['instagram'],
            'whatsapp' => $validate['whatsapp'],
        ]);

        $existingGalleries = Gallery::pluck('foto')->toArray();

        $oldPhotos = $request->old_foto ?? [];

        $photosToDelete = array_diff($existingGalleries, $oldPhotos);

        foreach ($photosToDelete as $photo) {
            Storage::delete($photo);
            Gallery::where('foto', $photo)->delete();
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $photo) {
                $originalName = $photo->getClientOriginalName();

                $filename =  uniqid() . '-'  . $originalName;
                $photoPath = $photo->storeAs('foto', $filename);

                Gallery::create([
                    'foto' => $photoPath,
                ]);
            }
        }

        Alert::toast(
            '<p style="color: white; margin-top: 10px;">Pengaturan berhasil diubah!</p>',
            'success'
        )
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('settings.index');
    }

    public function show(Settings $settings)
    {
        //
    }

    public function edit(Settings $settings)
    {
        //
    }

    public function update(Request $request, Settings $settings)
    {
        //
    }

    public function destroy(Settings $settings)
    {
        //
    }
}
