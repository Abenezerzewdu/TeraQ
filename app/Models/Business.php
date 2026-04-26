<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'name',
        'location',
        'owner_id',
        'is_active',
        'hero_image_path',
        'logo_path',
    ];

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Business $business) {
            $business->slug = Str::slug($business->name);
        });
    }
}
