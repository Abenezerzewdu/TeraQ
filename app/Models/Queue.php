<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Queue extends Model
{
    //
    protected $fillable = [
        'business_id',
        'name',
        'status',
        'avg_service_time',
    ];

     //  Queue belongs to a business
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    // Queue has many entries
    public function entries(): HasMany
    {
        return $this->hasMany(QueueEntry::class);
    }

    // Helper: only waiting users
    public function waitingEntries(): HasMany
    {
        return $this->hasMany(QueueEntry::class)
            ->where('status', 'waiting')
            ->orderBy('position');
    }

    //  Helper: current serving user
    public function currentServing()
    {
        return $this->hasOne(QueueEntry::class)
            ->where('status', 'serving');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($queue) {
        $queue->slug = Str::slug($queue->name . '-' . $queue->business_id);
    });
}

public function getRouteKeyName()
{
    return 'slug';
}

}
