<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $primaryKey = 'id_pengaduan';

    protected $fillable = [
        'nomor_pengaduan',
        'pelapor',
        'kondisi_masalah',
        'lokasi',
        'foto_pengaduan',
        'tanggal_pengaduan',
        'jam_pengaduan',
        'keterangan_masalah',
        'foto_penanganan',
        'uraian_masalah',
        'jam_penyelesaian',
        'tanggal_penyelesaian',
        'durasi_penyelesaian',
        'penyelesaian_masalah',
        'status',
    ];

    public function detailPengaduans()
    {
        return $this->hasMany(DetailPengaduan::class, 'pengaduan_id', 'id_pengaduan');
    }
}
