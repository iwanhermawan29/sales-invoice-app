<?php

// app/Models/Gallery.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'city',
        'title',
        'caption',
        'photo_path',
        'photo_mime',
        'photo_size',
        'taken_at',
        'contest_id',
        'uploaded_by',
        'is_published',
    ];

    protected $casts = [
        'taken_at' => 'date',
        'is_published' => 'boolean',
    ];

    // URL publik foto
    public function getUrlAttribute(): string
    {
        return Storage::url($this->photo_path);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
