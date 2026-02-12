<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';
    public $timestamps = false;

    protected $fillable = [
        'no_aduan',
        'tanggal_lapor',
        'isi_aduan',
        'lokasi',
        'latitude',
        'longitude',
        'foto',
        'masyarakat_id',
        'kategori_aduan_id',
        'akses_aduan_id',
        'status_aduan_id',
        'tanggal_selesai',
        'tanggal_target',
        'tanggal_mulai',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

    /**
     * Relasi Many-to-One dengan Masyarakat
     */
    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'masyarakat_id');
    }

    /**
     * Relasi Many-to-One dengan KategoriAduan
     */
    public function kategoriAduan()
    {
        return $this->belongsTo(KategoriAduan::class, 'kategori_aduan_id');
    }

    /**
     * Relasi Many-to-One dengan AksesAduan
     */
    public function aksesAduan()
    {
        return $this->belongsTo(AksesAduan::class, 'akses_aduan_id');
    }

    /**
     * Relasi Many-to-One dengan StatusAduan
     */
    public function statusAduan()
    {
        return $this->belongsTo(StatusAduan::class, 'status_aduan_id');
    }

    /**
     * Relasi One-to-Many dengan RiwayatStatusAduan
     */
    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusAduan::class, 'aduan_id');
    }

    /**
     * Relasi One-to-Many dengan TanggapanAduan
     */
    public function tanggapan()
    {
        return $this->hasMany(TanggapanAduan::class, 'aduan_id');
    }
}
