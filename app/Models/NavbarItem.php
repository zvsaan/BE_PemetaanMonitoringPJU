<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavbarItem extends Model
{
    protected $fillable = [
        'title', 
        'url',
        'type',
        'parent_id',
        'order',
        'is_published'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(NavbarItem::class, 'parent_id')->orderBy('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavbarItem::class, 'parent_id');
    }
}