<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use App\Services\LegacyNews\ImportResult;
use App\Services\LegacyNews\NewsUrlImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    /** Hard cap per run: the modal request would time out well before this on a slow source site. */
    public const MAX_URLS_PER_RUN = 50;

    protected function getHeaderActions(): array
    {
        return [
            $this->importFromUrlAction(),
            CreateAction::make(),
        ];
    }

    protected function importFromUrlAction(): Action
    {
        return Action::make('importFromUrl')
            ->label('Import from URL')
            ->icon(Heroicon::OutlinedArrowDownTray)
            ->color('gray')
            ->modalHeading('Import news from kryeministri.rks-gov.net')
            ->modalDescription('Fetches the Albanian, English and Serbian versions of each article, downloads the cover image, and saves them as drafts for review.')
            ->modalSubmitActionLabel('Import')
            ->schema([
                Textarea::make('urls')
                    ->label('Article links')
                    ->placeholder("https://kryeministri.rks-gov.net/news/...\nhttps://kryeministri.rks-gov.net/news/...")
                    ->helperText('One link per line.')
                    ->rows(6)
                    ->requiredWithout('csv'),
                FileUpload::make('csv')
                    ->label('…or a CSV of links')
                    ->disk('local')
                    ->directory('tmp/imports')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                    ->helperText('Every cell that looks like a link is imported.')
                    ->requiredWithout('urls'),
                Select::make('category')
                    ->options(['news' => 'News', 'bulletin' => 'Bulletin', 'report' => 'Report'])
                    ->default('news')
                    ->required(),
            ])
            ->action(function (array $data) {
                $urls = $this->collectUrls($data['urls'] ?? null, $data['csv'] ?? null);

                if ($urls === []) {
                    Notification::make()->title('No links found')->warning()->send();

                    return;
                }

                $truncated = count($urls) > self::MAX_URLS_PER_RUN;
                $urls = array_slice($urls, 0, self::MAX_URLS_PER_RUN);

                $importer = app(NewsUrlImporter::class);
                $author = auth()->user();
                $results = array_map(fn (string $url) => $importer->import($url, $data['category'], $author), $urls);

                $this->notifyImportOutcome($results, $truncated);

                $created = array_values(array_filter($results, fn (ImportResult $r) => $r->isCreated()));

                if (count($created) === 1 && count($results) === 1) {
                    $this->redirect(NewsResource::getUrl('edit', ['record' => $created[0]->record]));
                }
            });
    }

    /** @return array<int, string> unique links, in the order given */
    protected function collectUrls(?string $pasted, ?string $csvPath): array
    {
        $urls = preg_split('/\R+/', (string) $pasted) ?: [];

        if ($csvPath && Storage::disk('local')->exists($csvPath)) {
            try {
                $reader = Reader::createFromString(Storage::disk('local')->get($csvPath));

                foreach ($reader->getRecords() as $row) {
                    foreach ($row as $cell) {
                        if (preg_match('#^\s*https?://#i', (string) $cell)) {
                            $urls[] = $cell;
                        }
                    }
                }
            } finally {
                Storage::disk('local')->delete($csvPath);
            }
        }

        $urls = array_map('trim', $urls);
        $urls = array_filter($urls, fn (string $u) => $u !== '');

        return array_values(array_unique($urls));
    }

    /** @param  array<int, ImportResult>  $results */
    protected function notifyImportOutcome(array $results, bool $truncated): void
    {
        $count = fn (string $status) => count(array_filter($results, fn (ImportResult $r) => $r->status === $status));
        $created = $count(ImportResult::CREATED);
        $skipped = $count(ImportResult::SKIPPED);
        $failed = $count(ImportResult::FAILED);

        $lines = [];

        foreach ($results as $result) {
            if ($result->isFailed()) {
                $lines[] = "Failed: {$result->url} — {$result->message}";
            } elseif ($result->isCreated() && $result->message) {
                $lines[] = "Note: {$result->url} — {$result->message}";
            }
        }

        if ($truncated) {
            $lines[] = 'Only the first '.self::MAX_URLS_PER_RUN.' links were processed. Run the import again for the rest.';
        }

        $notification = Notification::make()
            ->title("Imported {$created}, skipped {$skipped}, failed {$failed}")
            ->body($lines === [] ? null : implode("\n", $lines))
            ->persistent();

        match (true) {
            $failed > 0 && $created === 0 => $notification->danger(),
            $failed > 0 || $skipped > 0 => $notification->warning(),
            default => $notification->success(),
        };

        $notification->send();
    }
}
