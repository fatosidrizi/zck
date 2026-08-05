<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Community extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'population', 'region',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
