<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    //
     protected $fillable = [
        'name',
        'location',
        'owner_id',
        'is_active',
    ];
 // A business has many queues
    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }
}
