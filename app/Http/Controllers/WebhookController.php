<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        // Buat instance dari Midtrans Notification
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error instantiating notification.'], 500);
        }

        // Ambil data dari notifikasi
        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKey = $notification->signature_key;
        $transactionStatus = $notification->transaction_status;

        // Buat signature key pembanding untuk verifikasi keamanan
        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

        // Validasi signature: Pastikan notifikasi ini benar-benar dari Midtrans
        if ($signatureKey !== $mySignatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari tagihan di database berdasarkan order_id
        $tagihan = Tagihan::where('midtrans_order_id', $orderId)->first();

        // Jika tagihan ditemukan, update statusnya
        if ($tagihan) {
            // Jika status transaksi 'settlement' atau 'capture', update status tagihan menjadi 'lunas'
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $tagihan->status = 'lunas';
                $tagihan->save();
            }
            // Anda juga bisa menambahkan logika untuk status lain di sini,
            // misalnya 'pending', 'expire', 'cancel', dll.
        }

        // Beri tahu Midtrans bahwa notifikasi sudah diterima
        return response()->json(['message' => 'Notification processed successfully.']);
    }
}