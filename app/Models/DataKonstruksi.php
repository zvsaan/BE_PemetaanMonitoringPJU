<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKonstruksi extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'data_konstruksis';

    // Primary key yang digunakan
    protected $primaryKey = 'id_konstruksi';

    // Format waktu yang digunakan
    public $timestamps = true;

    protected $fillable = [
        'pju_id',
        'tanggal_penggalian',
        'tanggal_pengecoran',
        'pemasangan_tiang',
        'grounding_finishing',
        'pemasangan_aksesories',
        'pemasangan_mcb',
    ];

    // Relasi ke PJU
    public function pju()
    {
        return $this->belongsTo(DataPju::class, 'pju_id', 'id_pju');
    }
}
