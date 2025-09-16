<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_name',
        'year',
        'target_amount',
        'notes',
    ];

    /**
     * The user (agent) for this event target.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
