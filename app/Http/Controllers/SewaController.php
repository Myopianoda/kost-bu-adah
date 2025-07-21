<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Sewa;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SewaController extends Controller
{
    public function create(Request $request)
    {
        $unit = Unit::findOrFail($request->query('unit'));
        $daftar_penyewa = Penyewa::orderBy('nama_lengkap')->get();

        return view('sewa.create', compact('unit', 'daftar_penyewa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'penyewa_id' => 'required|exists:penyewas,id',
            'tanggal_mulai' => 'required|date',
        ]);

        // Gunakan transaction untuk memastikan kedua proses berhasil
        DB::transaction(function () use ($request) {
            // 1. Buat data sewa baru
            Sewa::create([
                'unit_id' => $request->unit_id,
                'penyewa_id' => $request->penyewa_id,
                'tanggal_mulai' => $request->tanggal_mulai,
            ]);

            // 2. Update status unit menjadi 'terisi'
            $unit = Unit::find($request->unit_id);
            $unit->status = 'terisi';
            $unit->save();
        });

        return redirect()->route('units.index')->with('success', 'Unit berhasil disewakan!');
    }
}