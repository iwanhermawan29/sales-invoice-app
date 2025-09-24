<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    public const STATUS_PENDING  = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;

    protected $fillable = [
        'customer_name',
        'sale_date',
        'product_id',
        'case_level',
        'premium',
        // optional kalau mau mass-assign saat approve via controller:
        'status',
        'approved_by',
        'approved_at',
        'approval_note',
        'user_id',
    ];

    protected $casts = [
        'sale_date'   => 'date',
        'premium'     => 'decimal:2',
        'approved_at' => 'datetime',
        'status'      => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessor label & badge color (opsional)
    public function getStatusLabelAttribute(): string
    {
        return [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected'][$this->status] ?? 'Unknown';
    }
    public function getStatusColorAttribute(): string
    {
        return [0 => 'bg-amber-100 text-amber-700', 1 => 'bg-green-100 text-green-700', 2 => 'bg-rose-100 text-rose-700'][$this->status] ?? 'bg-gray-100 text-gray-700';
    }

    // Scopes
    public function scopeApproved($q)
    {
        return $q->where('status', self::STATUS_APPROVED);
    }
    public function scopePending($q)
    {
        return $q->where('status', self::STATUS_PENDING);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // default fk: user_id
    }
}
