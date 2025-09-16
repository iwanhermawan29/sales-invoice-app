<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'customer_id',
        'created_by',
        'order_date',
        'status',
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke user yang membuat SO
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Detail items
    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}
