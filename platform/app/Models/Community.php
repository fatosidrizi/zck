<?php

namespace App\Models;

use App\Models\Concerns\TranslatesWithFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Community extends Model
{
    use HasTranslations;
    use TranslatesWithFallback;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'population', 'region',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * The image to show for this community, or null when there is none.
     *
     * An image uploaded through the admin panel wins. Otherwise a file shipped
     * with the code under public/images/communities/{slug}.* is used, so the
     * flags carried over from the legacy site display without a database row
     * or a storage upload on the server.
     */
    public function imageUrl(): ?string
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        foreach (['svg', 'png', 'jpg', 'jpeg', 'webp'] as $ext) {
            $relative = "images/communities/{$this->slug}.{$ext}";

            if (is_file(public_path($relative))) {
                return asset($relative);
            }
        }

        return null;
    }
}
