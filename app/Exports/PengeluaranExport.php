<?php
namespace App\Exports;
use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PengeluaranExport implements FromQuery, WithHeadings, WithMapping
{
    public function query() { return Pengeluaran::query()->latest('tanggal'); }
    public function headings(): array { return ['ID', 'Tanggal', 'Keterangan', 'Jumlah (Rp)']; }
    public function map($pengeluaran): array {
        return [
            $pengeluaran->id,
            Carbon::parse($pengeluaran->tanggal)->format('d-m-Y'),
            $pengeluaran->keterangan,
            $pengeluaran->jumlah,
        ];
    }
}