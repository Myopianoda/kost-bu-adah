<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PortalController extends Controller
{
    public function index()
    {
        // Ambil data penyewa yang sedang login
        $penyewa = Auth::guard('penyewa')->user();

        /** @var \App\Models\Penyewa $penyewa */

        // Ambil semua data sewa milik penyewa ini,
        // beserta semua tagihan yang terkait dengan sewa tersebut.
        $sewaPenyewa = $penyewa->sewa()
                               ->with(['unit', 'tagihan' => function ($query) {
                                   // Urutkan tagihan dari yang terbaru
                                   $query->orderBy('tanggal_tagihan', 'desc');
                               }])
                               ->where('status', 'aktif') // Hanya ambil sewa yang masih aktif
                               ->get();

        // Kirim data ke view
        return view('portal.dashboard', compact('penyewa', 'sewaPenyewa'));
    }

    public function editProfil()
    {
        return view('portal.profil');
    }

    /**
     * Memproses update password penyewa.
     */
    public function updateProfil(Request $request)
    {
        // --- BLOK 1: AMBIL DATA PENYEWA (YANG HILANG) ---
        // Kita harus mengambil data penyewa yang sedang login
        $penyewa = Auth::guard('penyewa')->user();
        
        /** @var \App\Models\Penyewa $penyewa */ // <-- Ini untuk "menenangkan" Intelephense

        // --- BLOK 2: VALIDASI INPUT (YANG HILANG) ---
        // Kita harus memvalidasi data dari form
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        // --- BLOK 3: CEK PASSWORD LAMA ---
        // Kode ini sudah benar di screenshot Anda
        if (!Hash::check($validated['current_password'], $penyewa->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ])->onlyInput('current_password');
        }

        // --- BLOK 4: SIMPAN PASSWORD BARU ---
        // Kode ini juga sudah benar di screenshot Anda
        $penyewa->password = Hash::make($validated['password']);
        $penyewa->save(); // Garis merahnya akan hilang setelah Blok 1 ditambahkan

        // --- BLOK 5: REDIRECT ---
        return redirect()->route('penyewa.profil.edit')->with('status', 'password-updated');
    }
}