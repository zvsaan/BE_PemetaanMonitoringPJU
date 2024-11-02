<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coba extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapisan',
        'no_app',
        'no_tiang_lama',
        'no_tiang_baru',
        'nama_jalan',
        'kecamatan',
        'tinggi_tiang_m',
        'jenis_tiang',
        'daya_lampu_w',
        'status_jalan',
        'longitude',
        'latitude',
    ];
}
