<?php

// app/Models/SalesClosing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_target_id',
        'product_id',
        'customer',
        'policy_number',
        'premium_amount',
        'closing_date',
        'notes',
    ];

    public function salesTarget()
    {
        return $this->belongsTo(SalesTarget::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
