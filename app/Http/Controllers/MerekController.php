<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MerekController extends Controller
{

    public function index()
    {
        $mereks = Merek::latest()->get();
        return view('dashboard.pages.admin.merek.index', compact('mereks'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_merek' => ['required', 'max:255', 'unique:mereks'],
        ]);

        $merek =  Merek::create($validate);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $merek->nama_merek . ' berhasil ditambahkan!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('merek.index');
    }

    public function show(Merek $merek)
    {
        //
    }

    public function edit(Merek $merek)
    {
        //
    }

    public function update(Request $request, Merek $merek)
    {
        $validate = $request->validate([
            'nama_merek' => ['required', 'max:255', 'unique:mereks,nama_merek,' . $merek->id],
        ]);

        $merek->update($validate);

        Alert::toast('<p style="color: white; margin-top: 10px;">' . $merek->nama_merek . ' berhasil diubah!</p>', 'success')
            ->toHtml()
            ->background('#333A73');

        return redirect()->route('merek.index');
    }

    public function destroy(Merek $merek)
    {
        $merek->delete();
        Alert::toast('<p style="color: white; margin-top: 10px;">' . $merek->nama_merek . ' berhasil di hapus!</p>', 'success')
            ->toHtml()
            ->background('#333A73');
        return redirect()->route('merek.index');
    }
}
