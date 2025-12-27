<?php

namespace App\Http\Controllers;

use App\Models\Kebijakan;
use Illuminate\Http\Request;

class KebijakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Kebijakan::first();
        //dd($data);
        return view('kebijakan.index')->with([
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kebijakan $kebijakan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kebijakan $kebijakan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Kebijakan::findOrFail($id);

        $data->update($request->only([
            'max_waktu_peminjaman',
            'max_jml_buku',
            'denda'
        ]));

        return redirect()
            ->route('kebijakan.index')
            ->with('message_update', 'Data Sudah diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kebijakan $kebijakan)
    {
        //
    }
}
