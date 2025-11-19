<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyewaLoginController extends Controller
{
    /**
     * Menampilkan halaman (view) form login.
     */
    public function create()
    {
        return view('auth.penyewa-login');
    }

    /**
     * Memproses upaya login dari penyewa.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'telepon' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Coba lakukan login menggunakan guard 'penyewa'
        if (Auth::guard('penyewa')->attempt($credentials, $request->boolean('remember'))) {

            // 3. Jika berhasil, regenerasi session
            $request->session()->regenerate();

            // 4. Redirect ke "halaman utama" penyewa
            // (Untuk sekarang, kita redirect ke halaman depan dulu)
            return redirect()->route('penyewa.dashboard'); 
        }

        // 5. Jika gagal, kembali ke form login dengan pesan error
        return back()->withErrors([
            'telepon' => 'Nomor Telepon atau Password tidak sesuai.',
        ])->onlyInput('telepon');
    }

    // Tambahkan fungsi baru ini di bawah fungsi store()

    /**
     * Melakukan logout penyewa.
     */
    public function destroy(Request $request)
    {
        Auth::guard('penyewa')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama
    }
}