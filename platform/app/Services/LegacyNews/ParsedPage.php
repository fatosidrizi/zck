<?php

namespace App\Services\LegacyNews;

use Carbon\CarbonImmutable;

/** One language version of an article, as parsed from a single HTML page. */
final class ParsedPage
{
    /**
     * @param  array<string, string>  $translations  WPML hreflang => absolute URL of that language version
     */
    public function __construct(
        public readonly string $url,
        public readonly ?string $locale,
        public readonly string $title,
        public readonly string $body,
        public readonly ?string $imageUrl,
        public readonly ?CarbonImmutable $publishedAt,
        public readonly array $translations,
    ) {}
}
