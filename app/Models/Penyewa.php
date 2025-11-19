<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// UBAH INI: class Penyewa extends Model
// MENJADI INI:
class Penyewa extends Authenticatable
{
    // TAMBAHKAN TRAIT INI
    use HasFactory, Notifiable;

    protected $table = 'penyewas'; // Kita tetap pakai 'penyewas' sesuai standar

    // Kita harus menyembunyikan password saat data diambil
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'nama_lengkap',
        'telepon',
        'nomor_ktp',
        'alamat_asal',
        'foto_ktp',
        'password', // <-- Tambahkan password ke fillable
    ];

    // ... (fungsi relasi sewa() yang sudah ada biarkan saja) ...
    public function sewa()
    {
        return $this->hasMany(Sewa::class);
    }
}