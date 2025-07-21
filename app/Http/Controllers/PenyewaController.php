<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daftar_penyewa = Penyewa::latest()->get();

        return view('penyewa.index', compact('daftar_penyewa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penyewa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon',
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp',
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }

        Penyewa::create($validated);

        return redirect()->route('penyewa.index')->with('success', 'Penyewa baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Penyewa $penyewa)
    {
    return view('penyewa.edit', compact('penyewa'));
    }

    public function update(Request $request, Penyewa $penyewa)
    {

        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon,' . $penyewa->id,
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp,' . $penyewa->id,
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_ktp')) {
            if ($penyewa->foto_ktp) {
                Storage::disk('public')->delete($penyewa->foto_ktp);
            }
            
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }
        $penyewa->update($validated);

        return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewa $penyewa)
    {
    if ($penyewa->foto_ktp) {
        Storage::disk('public')->delete($penyewa->foto_ktp);
    }
    $penyewa->delete();

    return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil dihapus!');
    }
}
