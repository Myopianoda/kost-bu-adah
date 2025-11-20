<?php

namespace App\Console\Commands;

use App\Models\Sewa;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BuatTagihanBulanan extends Command
{
    protected $signature = 'app:buat-tagihan-bulanan';
    protected $description = 'Membuat tagihan bulanan untuk semua sewa yang aktif';

    public function handle()
    {
        $this->info('Memulai proses pembuatan tagihan bulanan...');

        // 1. FIX: Eager Load 'penyewa' untuk menghindari N+1 Query Problem
        $sewaAktif = Sewa::where('status', 'aktif')
            ->with(['unit', 'penyewa']) 
            ->get();

        $jumlahTagihanDibuat = 0;

        foreach ($sewaAktif as $sewa) {
            try {
                $today = Carbon::today();
                $tanggalMulai = Carbon::parse($sewa->tanggal_mulai);

                // 2. FIX: Logika untuk tanggal 29, 30, 31
                // Jika tanggal mulai sewa lebih besar dari jumlah hari di bulan ini (misal mulai tgl 31, tapi skrg Februari),
                // maka tagihan dimajukan ke tanggal terakhir bulan ini (tgl 28/29).
                $tglJadwalTagihan = $tanggalMulai->day;
                $hariTerakhirBulanIni = $today->daysInMonth;
                
                if ($tglJadwalTagihan > $hariTerakhirBulanIni) {
                    $tglJadwalTagihan = $hariTerakhirBulanIni;
                }

                // Cek apakah HARI INI adalah hari jadwal tagihan
                if ($today->day == $tglJadwalTagihan) {
                    
                    // Cek apakah tagihan bulan & tahun ini SUDAH ADA biar tidak duplikat
                    $sudahAdaTagihan = Tagihan::where('sewa_id', $sewa->id)
                        ->whereYear('tanggal_tagihan', $today->year)
                        ->whereMonth('tanggal_tagihan', $today->month)
                        ->exists();

                    if (!$sudahAdaTagihan) {
                        // Gunakan Transaksi Database untuk keamanan data
                        DB::transaction(function () use ($sewa, $today) {
                            Tagihan::create([
                                'sewa_id' => $sewa->id,
                                'jumlah' => $sewa->unit->price,
                                'tanggal_tagihan' => $today,
                                // Jatuh tempo 10 hari setelah hari ini
                                'tanggal_jatuh_tempo' => $today->copy()->addDays(10), 
                                'status' => 'belum_bayar', // Pastikan ada default status
                            ]);
                        });

                        $this->info("✓ Tagihan dibuat: {$sewa->penyewa->nama_lengkap} (Unit: {$sewa->unit->name})");
                        $jumlahTagihanDibuat++;
                    }
                }
            } catch (\Exception $e) {
                // 3. Error Handling: Supaya jika 1 error, yang lain tetap jalan
                $this->error("Gagal memproses sewa ID {$sewa->id}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. Total tagihan baru: {$jumlahTagihanDibuat}");
        return 0;
    }
}