<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class SalesTarget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'period',
        'target_amount',
        'notes',
    ];

    public function closings()
    {
        return $this->hasMany(SalesClosing::class);
    }

    /**
     * Hitung sisa target: target_amount dikurangi total premi
     */
    public function getRemainingTargetAttribute(): float
    {
        $totalClosed = $this->closings()
            ->whereBetween('closing_date', [
                // jika period = 'YYYY-MM', hitung per bulan:
                $this->period . '-01',
                $this->period . '-' . now()->endOfMonth()->day
            ])
            ->sum('premium_amount');

        return round($this->target_amount - $totalClosed, 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
