<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPanel extends Model
{
    use HasFactory;

    protected $table = 'riwayat_panels';
    
    protected $primaryKey = 'id_riwayat_panel';

    protected $fillable = [
        'panel_id',
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
    public function panel()
    {
        return $this->belongsTo(DataPanel::class, 'panel_id');
    }
}
