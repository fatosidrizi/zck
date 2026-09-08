<?php

namespace App\Services\LegacyNews;

use App\Models\News;

final class ImportResult
{
    public const CREATED = 'created';

    public const SKIPPED = 'skipped';

    public const FAILED = 'failed';

    private function __construct(
        public readonly string $url,
        public readonly string $status,
        public readonly ?string $message,
        public readonly ?News $record,
    ) {}

    public static function created(string $url, News $record, ?string $note = null): self
    {
        return new self($url, self::CREATED, $note, $record);
    }

    public static function skipped(string $url, string $reason, ?News $record = null): self
    {
        return new self($url, self::SKIPPED, $reason, $record);
    }

    public static function failed(string $url, string $reason): self
    {
        return new self($url, self::FAILED, $reason, null);
    }

    public function isCreated(): bool
    {
        return $this->status === self::CREATED;
    }

    public function isSkipped(): bool
    {
        return $this->status === self::SKIPPED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::FAILED;
    }
}
