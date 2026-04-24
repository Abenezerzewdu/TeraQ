<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Business extends Model
{
    //
     protected $fillable = [
        'name',
        'location',
        'owner_id',
        'is_active',
        'hero_image_path',
        'logo_path',
    ];
 // A business has many queues
    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($business) {
        $business->slug = Str::slug($business->name);
    });
}
public function getRouteKeyName()
{
    return 'slug';
}
}
