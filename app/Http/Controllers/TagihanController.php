<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        $semua_tagihan = Tagihan::with(['sewa.penyewa', 'sewa.unit'])
                            ->latest()
                            ->get();

        return view('tagihan.index', compact('semua_tagihan'));
    }

    // ... di dalam class TagihanController ...

    public function bayar(Tagihan $tagihan)
    {
        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // Buat ID Order yang unik
        $orderId = 'KOST-BU-ADAH-' . $tagihan->id . '-' . time();

        // Simpan Order ID ke tagihan untuk referensi
        $tagihan->midtrans_order_id = $orderId;
        $tagihan->save();

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $tagihan->jumlah,
            ],
            'customer_details' => [
                'first_name' => $tagihan->sewa->penyewa->nama_lengkap,
                'email' => 'dummy-customer@example.com', // Ganti dengan email penyewa jika ada
                'phone' => $tagihan->sewa->penyewa->telepon,
            ],
        ];

        // Dapatkan Snap Token dari Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Kirim Snap Token ke view
        return view('tagihan.bayar', compact('snapToken', 'tagihan'));
    }
}