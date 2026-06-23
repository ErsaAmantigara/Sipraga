<?php

namespace App\Http\Controllers;

use App\Models\KriteriaSaw;
use Illuminate\Http\Request;

class KriteriaSawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriteria = KriteriaSaw::orderBy('kriteria_saw_id', 'asc')->get();
        $totalBobot = KriteriaSaw::sum('bobot');

        return view('kriteria-saw.index', compact('kriteria', 'totalBobot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kriteria = KriteriaSaw::findOrFail($id);
        $totalBobotLain = KriteriaSaw::where('kriteria_saw_id', '!=', $id)->sum('bobot');
        $sisaBobot = 100 - $totalBobotLain;

        return view('kriteria-saw.edit', compact('kriteria', 'sisaBobot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kriteria = KriteriaSaw::findOrFail($id);

        $totalBobotLain = KriteriaSaw::where('kriteria_saw_id', '!=', $id)->sum('bobot');

        $request->validate([
            'kode_kriteria' => 'required|string|unique:kriteria_saw,kode_kriteria,' . $id . ',kriteria_saw_id',
            'nama_kriteria' => 'required|string',
            'bobot' => 'required|numeric|min:0|max:' . (100 - $totalBobotLain),
            'jenis' => 'required|in:benefit,cost',
        ]);

        $kriteria->update($request->all());

        return redirect()->route('kriteria-saw.index')->with('success', 'Kriteria berhasil diupdate.');
    }


}
