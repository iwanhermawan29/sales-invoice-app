<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TargetPenjualan extends Model
{
    use SoftDeletes;

    protected $table = 'targets_penjualan';

    protected $fillable = [
        'agent_id',
        'product_id',
        'period',
        'start_date',
        'end_date',
        'target_premium',
        'target_case',
        'title',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'target_premium' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
