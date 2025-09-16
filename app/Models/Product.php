<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'description',
    ];

    /**
     * Constants for product types.
     */
    public const TYPE_HEALTH = 'health';
    public const TYPE_LIFE   = 'life';

    public function closings()
    {
        return $this->hasMany(SalesClosing::class);
    }

    /**
     * Get human-readable label for type.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_HEALTH ? 'Asuransi Kesehatan' : 'Asuransi Jiwa';
    }
}
