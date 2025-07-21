<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyewa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'telepon',
        'nomor_ktp',
        'alamat_asal',
        'foto_ktp',
    ];

    public function sewa()
    {
        return $this->hasMany(Sewa::class);
    }
}
