<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection; // Kita akan ganti ini
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

// Kita akan implement 3 interface baru
class TagihanExport implements FromQuery, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // 1. Ini adalah query untuk mengambil data dari database
        // Kita ambil semua tagihan, beserta relasi penyewa dan unit
        return Tagihan::query()->with(['sewa.penyewa', 'sewa.unit']);
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // 2. Ini adalah baris judul (header) di file Excel nanti
        return [
            'ID Tagihan',
            'Nama Penyewa',
            'Unit',
            'Jumlah',
            'Status',
            'Tanggal Tagihan',
            'Tanggal Jatuh Tempo',
        ];
    }

    /**
    * @param mixed $tagihan
    * @return array
    */
    public function map($tagihan): array
    {
        // 3. Ini adalah data untuk setiap baris, sesuai urutan di headings()
        return [
            $tagihan->id,
            $tagihan->sewa->penyewa->nama_lengkap ?? 'N/A', // ?? 'N/A' untuk jaga-jaga jika data relasi terhapus
            $tagihan->sewa->unit->name ?? 'N/A',
            $tagihan->jumlah,
            $tagihan->status,
            Carbon::parse($tagihan->tanggal_tagihan)->format('d-m-Y'),
            Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d-m-Y'),
        ];
    }
}