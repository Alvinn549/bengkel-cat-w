<?php

namespace App\Http\Controllers;

use App\Models\Progres;
use App\Http\Requests\StoreProgresRequest;
use App\Http\Requests\UpdateProgresRequest;

class ProgresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProgresRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgresRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function show(Progres $progres)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function edit(Progres $progres)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProgresRequest  $request
     * @param  \App\Models\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgresRequest $request, Progres $progres)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function destroy(Progres $progres)
    {
        //
    }
}
