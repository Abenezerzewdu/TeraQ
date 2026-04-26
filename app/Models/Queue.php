<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Queue extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'status',
        'avg_service_time',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(QueueEntry::class);
    }

    public function waitingEntries(): HasMany
    {
        return $this->hasMany(QueueEntry::class)
            ->where('status', 'waiting')
            ->orderBy('position');
    }

    public function currentServing(): HasOne
    {
        return $this->hasOne(QueueEntry::class)->where('status', 'serving');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Queue $queue) {
            $queue->slug = Str::slug($queue->name . '-' . $queue->business_id);
        });
    }
}
