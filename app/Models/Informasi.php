<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasis';

    // protected $table = 'informasis';
    protected $primaryKey = 'id_informasis';

    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'tanggal_publikasi',
    ];
}