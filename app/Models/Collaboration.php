<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Collaboration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'website_url',
        'image_path',
        'image_mime',
        'image_size',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'bool',
        'is_active'   => 'bool',
    ];

    // URL publik logo
    public function getUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    // Scope aktif
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
