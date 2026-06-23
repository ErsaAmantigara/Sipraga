<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $cabang = Cabang::paginate(10);
        return view('cabang.index', compact('cabang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|unique:cabang',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        Cabang::create($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('cabang.edit', compact('cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cabang = Cabang::findOrFail($id);

        $request->validate([
            'nama_cabang' => 'required|string|unique:cabang,nama_cabang,' . $id . ',cabang_id',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);


        $cabang->update($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
