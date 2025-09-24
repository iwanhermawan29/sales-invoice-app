<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'nama_kontes',
        'target_premi',
        'target_case',
        'periode',      // monthly | quarterly | annual
        'flyer_path',
        'flyer_mime',
        'flyer_size',
        'tanggal_mulai',
        'tanggal_selesai' // KB
    ];

    // URL publik untuk flyer (jika disimpan di disk 'public')
    public function getFlyerUrlAttribute(): ?string
    {
        return $this->flyer_path ? asset('storage/' . $this->flyer_path) : null;
    }

    // app/Models/Contest.php
    public function media()
    {
        return $this->hasMany(\App\Models\ContestMedia::class)->orderBy('sort_order')->orderBy('id');
    }
    public function photos()
    {
        return $this->media()->where('type', 'photo');
    }
    public function logos()
    {
        return $this->media()->where('type', 'logo');
    }

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
