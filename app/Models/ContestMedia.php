<?php

// app/Models/ContestMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestMedia extends Model
{
    protected $fillable = [
        'contest_id',
        'type',
        'title',
        'caption',
        'path',
        'mime',
        'size',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
