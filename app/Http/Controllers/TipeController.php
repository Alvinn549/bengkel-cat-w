<?php

namespace App\Http\Controllers;

use App\Models\Tipe;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TipeController extends Controller
{
    public function index()
    {
        $pageTitle = 'Tipe';
        $tipes = Tipe::latest()->get();

        return view('dashboard.pages.admin.tipe.index', compact(
            'pageTitle',
            'tipes'
        ));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_tipe' => ['required', 'max:255', 'unique:tipes'],
        ]);

        $tipe =  Tipe::create($validate);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $tipe->nama_tipe . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('tipe.index');
    }

    public function update(Request $request, Tipe $tipe)
    {
        $validate = $request->validate([
            'nama_tipe' => ['required', 'max:255', 'unique:tipes,nama_tipe,' . $tipe->id],
        ]);

        $tipe->update($validate);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $tipe->nama_tipe . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('tipe.index');
    }

    public function destroy(Tipe $tipe)
    {
        $tipe->delete();

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $tipe->nama_tipe . ' berhasil di hapus!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('tipe.index');
    }
}
