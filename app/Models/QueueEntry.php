<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueEntry extends Model
{
    //user entry to queue
     protected $fillable = [
        'queue_id',
        'user_name',
        'device_id',
        'phone',
        'position',
        'status',
        'joined_at',
        'served_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    //  Entry belongs to a queue
    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    // Helper: check if active
    public function isActive(): bool
    {
        return in_array($this->status, ['waiting', 'serving']);
    }
}
