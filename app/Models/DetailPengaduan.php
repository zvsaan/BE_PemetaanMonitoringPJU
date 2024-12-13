<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengaduan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengaduan';

    protected $primaryKey = 'id_detail_pengaduan';

    protected $fillable = [
        'pengaduan_id',
        'panel_id',
        'pju_id',
    ];

    /**
     * Relasi ke Pengaduan
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'id_pengaduan');
    }

    /**
     * Relasi ke Panel
     */
    public function panel()
    {
        return $this->belongsTo(DataPanel::class, 'panel_id', 'id_panel');
    }

    /**
     * Relasi ke PJU
     */
    public function pju()
    {
        return $this->belongsTo(DataPJU::class, 'pju_id', 'id_pju');
    }
}