<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengeluaranExport;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran.
     */
    public function index()
    {
        // Ambil semua data, urutkan dari yang terbaru
        $daftarPengeluaran = Pengeluaran::latest()->get();

        // Kirim data ke view
        return view('pengeluaran.index', compact('daftarPengeluaran'));
    }

    /**
     * Menampilkan form untuk menambah pengeluaran baru.
     */
    public function create()
    {
        return view('pengeluaran.create');
    }

    /**
     * Menyimpan pengeluaran baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'tanggal' => 'required|date',
        ]);

        // 2. Simpan ke database
        Pengeluaran::create($validated);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil disimpan!');
    }

    /**
     * (Fungsi show tidak kita gunakan)
     */
    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * (Fungsi edit akan kita buat nanti)
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * (Fungsi update akan kita buat nanti)
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'tanggal' => 'required|date',
        ]);

        // 2. Update data di database
        $pengeluaran->update($validated);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil di-update!');
    }

    /**
     * (Fungsi destroy akan kita buat nanti)
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        // 1. Hapus data
        $pengeluaran->delete();

        // 2. Redirect kembali dengan pesan sukses
        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil dihapus!');
    }
    
    public function exportExcel() 
    {
        return Excel::download(new PengeluaranExport, 'laporan_pengeluaran.xlsx');
    }
}