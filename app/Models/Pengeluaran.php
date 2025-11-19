<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'keterangan',
        'jumlah',
        'tanggal',
    ];
}