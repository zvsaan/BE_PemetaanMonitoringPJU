<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPJU extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pjus';
    
    protected $primaryKey = 'id_riwayat_pju';

    protected $fillable = [
        'pju_id',
        'lokasi',
        'tanggal_masalah',
        'jam_masalah',
        'keterangan_masalah',
        'uraian_masalah',
        'tanggal_penyelesaian',
        'jam_penyelesaian',
        'durasi_penyelesaian',
        'penyelesaian_masalah',
        'nomer_rujukan',
        'status',
    ];

    /**
     * Definisikan hubungan dengan model Pju
     */
    public function pju()
    {
        return $this->belongsTo(DataPJU::class, 'pju_id');
    }
}