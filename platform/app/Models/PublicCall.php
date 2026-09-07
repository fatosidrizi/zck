<?php

namespace App\Models;

use App\Models\Concerns\TranslatesWithFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class PublicCall extends Model
{
    use HasTranslations;
    use TranslatesWithFallback;

    public array $translatable = ['title', 'body'];

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
