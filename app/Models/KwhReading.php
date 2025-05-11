<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KwhReading extends Model
{
    use HasFactory;

    protected $table = 'kwh_readings';
    protected $primaryKey = 'id_reading';

    protected $fillable = [
        'panel_id',
        'user_id',
        'kwh_number',
        'image_path',
        'notes'
    ];

    public function panel()
    {
        return $this->belongsTo(DataPanel::class, 'panel_id', 'id_panel');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}