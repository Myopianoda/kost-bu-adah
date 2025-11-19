<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- PENTING: Untuk enkripsi password
use Illuminate\Support\Facades\Storage; // <-- PENTING: Untuk hapus file
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenyewaExport;

class PenyewaController extends Controller
{
    /**
     * Menampilkan daftar semua penyewa.
     */
    public function index()
    {
        $daftar_penyewa = Penyewa::latest()->get();
        return view('penyewa.index', compact('daftar_penyewa'));
    }

    /**
     * Menampilkan form untuk menambah penyewa baru.
     */
    public function create()
    {
        return view('penyewa.create');
    }

    /**
     * Menyimpan penyewa baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input, termasuk password
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon',
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp',
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'        => 'required|string|min:8', // Perbaikan: min:8 (bukan min8)
        ]);

        // 2. Handle upload file
        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }

        // 3. Enkripsi (Hash) password sebelum disimpan
        $validated['password'] = Hash::make($validated['password']);

        // 4. Simpan ke database
        Penyewa::create($validated);

        return redirect()->route('penyewa.index')->with('success', 'Penyewa baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data penyewa.
     */
    public function edit(Penyewa $penyewa)
    {
        return view('penyewa.edit', compact('penyewa'));
    }

    /**
     * Mengupdate data penyewa di database.
     */
    public function update(Request $request, Penyewa $penyewa)
    {
        // 1. Validasi input, password boleh kosong (nullable)
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon,' . $penyewa->id,
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp,' . $penyewa->id,
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'        => 'nullable|string|min:8', // 'nullable' = Boleh dikosongi
        ]);

        // 2. Handle upload file (jika ada file baru)
        if ($request->hasFile('foto_ktp')) {
            // Hapus foto lama jika ada
            if ($penyewa->foto_ktp) {
                Storage::disk('public')->delete($penyewa->foto_ktp);
            }
            // Simpan foto baru
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }

        // 3. Logika khusus untuk password
        if ($request->filled('password')) {
            // Jika admin mengisi password baru, enkripsi password baru
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Jika admin mengosongi field password, hapus dari array
            // agar password lama di database tidak ditimpa
            unset($validated['password']);
        }

        // 4. Update data penyewa
        $penyewa->update($validated);

        return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil diupdate!');
    }

    /**
     * Menghapus data penyewa.
     */
    public function destroy(Penyewa $penyewa)
    {
        // Hapus foto dari storage jika ada
        if ($penyewa->foto_ktp) {
            Storage::disk('public')->delete($penyewa->foto_ktp);
        }

        // Hapus data dari database
        $penyewa->delete();

        return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil dihapus!');
    }

    public function exportExcel() 
    {
        return Excel::download(new PenyewaExport, 'laporan_penyewa.xlsx');
    }
}