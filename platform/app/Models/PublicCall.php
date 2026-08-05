<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicCall extends Model
{
    protected $fillable = [
        'title', 'slug', 'body', 'type', 'attachment', 'deadline', 'status', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isOpen(): bool
    {
        return !$this->deadline || $this->deadline->isFuture();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
