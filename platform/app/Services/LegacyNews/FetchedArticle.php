<?php

namespace App\Services\LegacyNews;

use Carbon\CarbonImmutable;

/**
 * One article from kryeministri.rks-gov.net, already split per app locale.
 * Produced by the fetcher, consumed by the importer; never touches the DB.
 */
final class FetchedArticle
{
    /**
     * @param  array<string, string>  $title  app locale => title
     * @param  array<string, string>  $body  app locale => sanitized HTML
     */
    public function __construct(
        public readonly string $sourceUrl,
        public readonly array $title,
        public readonly array $body,
        public readonly ?string $imageUrl,
        public readonly ?CarbonImmutable $publishedAt,
    ) {}

    public function hasContent(): bool
    {
        foreach ($this->title as $title) {
            if (trim($title) !== '') {
                return true;
            }
        }

        return false;
    }
}
