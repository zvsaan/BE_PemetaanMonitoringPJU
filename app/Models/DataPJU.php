<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPJU extends Model
{
    use HasFactory;

    protected $table = 'data_pjus';

    protected $primaryKey = 'id_pju';

    protected $fillable = [
        'panel_id',
        'lapisan',
        'no_tiang_lama',
        'no_tiang_baru',
        'nama_jalan',
        'kecamatan',
        'tinggi_tiang',
        'jenis_tiang',
        'daya_lampu',
        'status_jalan',
        'longitude',
        'latitude',
    ];

    public function panel()
    {
        return $this->belongsTo(DataPanel::class, 'panel_id', 'id_panel');
    }

    public function detailPengaduan()
    {
        return $this->hasMany(DetailPengaduan::class, 'pju_id', 'id_pju');
    }
}   