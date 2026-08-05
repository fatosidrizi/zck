<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'population', 'region',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
