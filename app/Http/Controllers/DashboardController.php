<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use App\Models\Tagihan;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung jumlah unit terisi dan kosong
        $unitTerisi = Unit::where('status', 'terisi')->count();
        $unitKosong = Unit::where('status', 'tersedia')->count();

        // 2. Hitung total pendapatan bulan ini (dari tagihan yang sudah lunas)
        $pendapatanBulanIni = Tagihan::where('status', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('jumlah');

        // 3. Hitung total tagihan yang belum dibayar
        $tagihanBelumBayar = Tagihan::where('status', 'belum_bayar')->sum('jumlah');

        // 4. Ambil daftar tagihan yang akan jatuh tempo (misal dalam 7 hari ke depan)
        $tagihanJatuhTempo = Tagihan::with(['sewa.penyewa', 'sewa.unit'])
            ->where('status', 'belum_bayar')
            ->where('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(7))
            ->where('tanggal_jatuh_tempo', '>=', Carbon::now())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();


        $dataPendapatan = Tagihan::select(
            DB::raw('SUM(jumlah) as total'),
            DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as bulan")
        )
        ->where('status', 'lunas')
        ->where('updated_at', '>=', Carbon::now()->subMonths(5)) // Ambil data 6 bulan (termasuk bulan ini)
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Proses data agar siap digunakan oleh Chart.js
        $chartLabels = [];
        $chartData = [];
        $tanggal = Carbon::now()->subMonths(5)->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $bulan = $tanggal->format('Y-m');
            $label = $tanggal->format('M Y'); // Contoh: Jul 2025
            $chartLabels[] = $label;

            // Cari data pendapatan untuk bulan ini
            $pendapatanBulan = $dataPendapatan->firstWhere('bulan', $bulan);
            $chartData[] = $pendapatanBulan ? $pendapatanBulan->total : 0;

            $tanggal->addMonth();
        }

        // Kirim semua data ke view
        return view('dashboard', compact(
            'unitTerisi',
            'unitKosong',
            'pendapatanBulanIni',
            'tagihanBelumBayar',
            'tagihanJatuhTempo',
            'chartLabels',
            'chartData'
        ));
    }
}