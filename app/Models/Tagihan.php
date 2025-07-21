<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use App\Models\Sewa;

class Tagihan extends Model
{
    use HasFactory;


    // Definisikan kolom yang boleh diisi
    protected $fillable = [
        'sewa_id',
        'jumlah',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
        'midtrans_order_id',
    ];

    // Definisikan relasi
    public function sewa()
    {
        return $this->belongsTo(Sewa::class);
    }
}
