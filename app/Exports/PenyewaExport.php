<?php
namespace App\Exports;
use App\Models\Penyewa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenyewaExport implements FromQuery, WithHeadings, WithMapping
{
    public function query() { return Penyewa::query(); }
    public function headings(): array { return ['ID', 'Nama Lengkap', 'Telepon', 'Nomor KTP', 'Alamat Asal', 'Tgl Daftar']; }
    public function map($penyewa): array {
        return [
            $penyewa->id,
            $penyewa->nama_lengkap,
            $penyewa->telepon,
            $penyewa->nomor_ktp,
            $penyewa->alamat_asal,
            $penyewa->created_at->format('d-m-Y'),
        ];
    }
}