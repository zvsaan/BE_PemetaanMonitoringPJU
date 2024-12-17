<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'title',
        'content',
        'author',
        'published_date',
        'image_url',
        'status',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $slug = Str::slug($berita->title);

            while (Berita::where('slug', $slug)->exists()) {
                $slug = Str::slug($berita->title) . '-' . Str::random(5);
            }

            $berita->slug = $slug;
        });
    }
}
