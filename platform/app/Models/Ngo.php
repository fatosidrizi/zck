<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ngo extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'contact_email', 'contact_phone',
        'website', 'location', 'category', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
