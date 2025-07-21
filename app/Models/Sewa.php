<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewa extends Model
    {
        protected $fillable = [
        'penyewa_id',
        'unit_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];


    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}
