<?php

namespace App\Console\Commands;

use App\Models\Sewa;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BuatTagihanBulanan extends Command
{
    protected $signature = 'app:buat-tagihan-bulanan';
    protected $description = 'Membuat tagihan bulanan untuk semua sewa yang aktif';

    public function handle()
    {
        $this->info('Memulai proses pembuatan tagihan bulanan...');
        // Ambil semua sewa yang statusnya masih 'aktif'
        $sewaAktif = Sewa::where('status', 'aktif')->with('unit')->get();

        foreach ($sewaAktif as $sewa) {
            $today = Carbon::today();
            $tanggalMulaiSewa = Carbon::parse($sewa->tanggal_mulai);

            // Cek apakah hari ini adalah tanggal pembuatan tagihan (sama dengan tanggal mulai sewa)heidi
            if ($tanggalMulaiSewa->day == $today->day) {
                // Cek apakah tagihan untuk bulan ini sudah ada
                $tagihanBulanIniAda = Tagihan::where('sewa_id', $sewa->id)
                    ->whereYear('tanggal_tagihan', $today->year)
                    ->whereMonth('tanggal_tagihan', $today->month)
                    ->exists();

                if (!$tagihanBulanIniAda) {
                    // Buat tagihan baru jika belum ada
                    Tagihan::create([
                        'sewa_id' => $sewa->id,
                        'jumlah' => $sewa->unit->price, // Ambil harga dari relasi unit
                        'tanggal_tagihan' => $today,
                        'tanggal_jatuh_tempo' => $today->copy()->addDays(10), // Jatuh tempo 10 hari dari sekarang
                    ]);
                    $this->info("Tagihan dibuat untuk penyewa: {$sewa->penyewa->nama_lengkap} di unit: {$sewa->unit->name}");
                }
            }
        }

        $this->info('Proses pembuatan tagihan bulanan selesai.');
        return 0;
    }
}