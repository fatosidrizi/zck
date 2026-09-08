<?php

namespace App\Models;

use App\Models\Concerns\TranslatesWithFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasTranslations;
    use TranslatesWithFallback;

    public array $translatable = ['title', 'body'];

    protected $fillable = [
        'title', 'slug', 'body', 'image', 'source_url', 'category', 'status', 'published_at', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
