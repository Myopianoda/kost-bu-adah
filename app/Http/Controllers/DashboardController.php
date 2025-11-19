<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
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
        // --- STATISTIK UNIT ---
        $unitTerisi = Unit::where('status', 'terisi')->count();
        $unitKosong = Unit::where('status', 'tersedia')->count();
        $tagihanBelumBayar = Tagihan::where('status', 'belum_bayar')->sum('jumlah');

        // --- STATISTIK KEUANGAN ---
        // 1. Ambil Pendapatan Bulan Ini (Lunas)
        $pendapatanBulanIni = Tagihan::where('status', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('jumlah');

        // 2. Ambil Pengeluaran Bulan Ini
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');
            
        // 3. Hitung Laba Bersih
        $labaBersihBulanIni = $pendapatanBulanIni - $pengeluaranBulanIni;
        
        // 4. Ambil daftar tagihan yang akan jatuh tempo (YANG HILANG TADI)
        $tagihanJatuhTempo = Tagihan::with(['sewa.penyewa', 'sewa.unit'])
            ->where('status', 'belum_bayar')
            ->where('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(7))
            ->where('tanggal_jatuh_tempo', '>=', Carbon::now())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // --- LOGIKA GRAFIK ---
        $dataPendapatan = Tagihan::select(
            DB::raw('SUM(jumlah) as total'),
            DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as bulan")
        )
        ->where('status', 'lunas')
        ->where('updated_at', '>=', Carbon::now()->subMonths(5))
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        $chartLabels = [];
        $chartData = [];
        $tanggal = Carbon::now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $bulan = $tanggal->format('Y-m');
            $label = $tanggal->format('M Y');
            $chartLabels[] = $label;
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
            'tagihanJatuhTempo', // <-- Sekarang sudah aman karena ada definisinya
            'pengeluaranBulanIni',
            'labaBersihBulanIni',
            'chartLabels',
            'chartData'
        ));
    }
}