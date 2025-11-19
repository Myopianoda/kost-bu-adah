<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Auth\Events\Validated;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua unit, DAN sertakan data sewa yang statusnya 'aktif'
        $units = Unit::with(['sewa' => function ($query) {
                            $query->where('status', 'aktif')->with('penyewa');
                        }])
                        ->latest()
                        ->get();

        // Kirim data ke view
        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')->with('success', 'Unit baru berhasil ditambahkan!');

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
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer',
    ]);

    $unit->update($validated);

    return redirect()->route('units.index')->with('success', 'Data unit berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        
    $unit->delete();

    return redirect()->route('units.index')->with('success', 'Data unit berhasil dihapus!');
    }
}
