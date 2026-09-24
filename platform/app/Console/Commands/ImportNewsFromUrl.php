<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\LegacyNews\NewsUrlImporter;
use Illuminate\Console\Command;

/**
 * Same importer the admin "Import from URL" button uses, for batch runs from
 * the server and for smoke-testing the parser against the live site.
 */
class ImportNewsFromUrl extends Command
{
    protected $signature = 'zck:import-news-url
                            {urls* : Article links on kryeministri.rks-gov.net, mkk.rks-gov.net or mapl.rks-gov.net}
                            {--category=news : news, bulletin or report}';

    protected $description = 'Import articles from kryeministri.rks-gov.net, mkk.rks-gov.net or mapl.rks-gov.net as draft news (sq/en/sr)';

    public function handle(NewsUrlImporter $importer): int
    {
        $author = User::where('role', UserRole::SuperAdmin)->first() ?? User::first();

        if (! $author) {
            $this->error('No user to attribute imported content to.');

            return self::FAILURE;
        }

        $counts = ['created' => 0, 'skipped' => 0, 'failed' => 0];

        foreach ($this->argument('urls') as $url) {
            $result = $importer->import($url, (string) $this->option('category'), $author);
            $counts[$result->status]++;

            $line = sprintf('[%s] %s', strtoupper($result->status), $result->url);

            if ($result->record) {
                $line .= sprintf(' -> #%d %s', $result->record->id, $result->record->slug);
            }

            if ($result->message) {
                $line .= " ({$result->message})";
            }

            $result->isFailed() ? $this->error($line) : $this->line($line);
        }

        $this->info(sprintf('Done: %d created, %d skipped, %d failed.', $counts['created'], $counts['skipped'], $counts['failed']));

        return $counts['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
