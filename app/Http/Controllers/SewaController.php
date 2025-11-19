<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Sewa;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function stop(Sewa $sewa)
    {
        // Gunakan transaction untuk memastikan 2 update ini berhasil bersamaan
        DB::transaction(function () use ($sewa) {
            
            // 1. Update status unit kembali menjadi "tersedia"
            // Kita perlu mengambil relasi 'unit' terlebih dahulu
            $unit = $sewa->unit;
            $unit->status = 'tersedia';
            $unit->save();

            // 2. Update status sewa menjadi "selesai"
            $sewa->status = 'selesai';
            $sewa->tanggal_selesai = Carbon::now();
            $sewa->save();
        });

        // Redirect kembali ke halaman unit dengan pesan sukses
        return redirect()->route('units.index')
                         ->with('success', 'Sewa telah berhasil dihentikan dan unit kembali tersedia.');
    }
}