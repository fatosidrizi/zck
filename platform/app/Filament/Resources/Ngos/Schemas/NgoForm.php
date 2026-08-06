<?php

namespace App\Filament\Resources\Ngos\Schemas;

use App\Http\Controllers\RegisterController;
use App\Models\Ngo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class NgoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Full-width, stacked. Side-by-side sections leave a tall void beside the
                // short one, which is what made this page look broken.
                static::applicationHeader()->columnSpanFull(),
                static::submittedApplication()->columnSpanFull(),
                static::publicProfile()->columnSpanFull(),
                static::registryRecord()->columnSpanFull(),
                static::internalNotes()->columnSpanFull(),
                static::decision()->columnSpanFull(),
            ]);
    }

    /**
     * Sits immediately above the decision buttons: the gaps in the profile spelled out
     * where the reviewer commits, not buried in a confirmation modal.
     */
    protected static function decision(): Section
    {
        return Section::make('Decision')
            ->description(fn (?Ngo $record) => $record?->isClosed()
                ? 'This case is closed. Reopening sends it back to the pending queue for a fresh review.'
                : 'Approving publishes the organization immediately. Rejecting closes the case.')
            ->icon('heroicon-o-check-badge')
            ->schema([
                Placeholder::make('publication_readiness')
                    ->hiddenLabel()
                    ->content(function (Ngo $record) {
                        $missing = $record->missingForPublication();

                        if ($record->isClosed() || $missing === []) {
                            return new HtmlString(
                                '<div style="padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#166534;font-size:13px;">'
                                .'The public profile is complete.</div>'
                            );
                        }

                        $items = array_map(
                            fn (string $item) => '<li style="margin-left:16px;list-style:disc;">'.e($item).'</li>',
                            $missing
                        );

                        return new HtmlString(
                            '<div style="padding:10px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;color:#92400e;font-size:13px;">'
                            .'<strong>The public profile is still incomplete.</strong> You can approve anyway and fill these in later.'
                            .'<ul style="margin-top:6px;">'.implode('', $items).'</ul></div>'
                        );
                    })
                    ->columnSpanFull(),
            ])
            ->hiddenOn('create');
    }

    /**
     * Where the application stands, before anything editable.
     */
    protected static function applicationHeader(): Placeholder
    {
        return Placeholder::make('application_header')
            ->hiddenLabel()
            ->content(function (?Ngo $record) {
                if (! $record) {
                    return '';
                }

                [$bg, $border, $text, $label] = match (true) {
                    $record->isPending() => ['#fffbeb', '#fde68a', '#92400e', 'Pending review'],
                    $record->isClosed() => ['#fef2f2', '#fecaca', '#991b1b', 'Closed &mdash; application rejected'],
                    $record->is_active => ['#f0fdf4', '#bbf7d0', '#166534', 'Approved &amp; published'],
                    default => ['#f8fafc', '#e2e8f0', '#334155', 'Approved &mdash; not published'],
                };

                $meta = [];

                if ($record->isClosed()) {
                    $meta[] = 'Read-only until the case is reopened';
                }

                if ($record->reference_number) {
                    $meta[] = 'Ref '.e($record->reference_number);
                }

                if ($record->submitted_at) {
                    $meta[] = 'Submitted '.e($record->submitted_at->format('d M Y H:i'));
                }

                if ($record->declared_at) {
                    $meta[] = 'Accuracy &amp; authority declared';
                }

                return new HtmlString(sprintf(
                    '<div style="padding:12px 16px;background:%s;border:1px solid %s;border-radius:8px;color:%s;">
                        <strong style="font-size:14px;">%s</strong>%s
                    </div>',
                    $bg,
                    $border,
                    $text,
                    $label,
                    $meta ? '<div style="font-size:12px;opacity:.85;margin-top:2px;">'.implode(' &middot; ', $meta).'</div>' : ''
                ));
            })
            ->hiddenOn('create');
    }

    /**
     * Exactly what the applicant sent, read-only. This is the evidence a reviewer
     * checks against — it must never look editable.
     */
    protected static function submittedApplication(): Section
    {
        return Section::make('Submitted application')
            ->description('Exactly as the applicant sent it. Read-only.')
            ->icon('heroicon-o-inbox-arrow-down')
            ->visible(fn (?Ngo $record) => $record?->wasSelfRegistered() ?? false)
            ->schema([
                static::submitted('name', 'Organization name'),
                static::submitted('abbreviation', 'Abbreviation'),
                static::submitted('registration_number', 'Registration number'),
                static::submitted('fiscal_number', 'Fiscal number'),
                static::submitted('primary_community', 'Primary community'),
                static::submitted('additional_communities', 'Additional communities'),
                static::submitted('activity_area', 'Area of activity', 'category'),
                static::submitted('responsible_person', 'Responsible person'),
                static::submitted(
                    'responsible_person_contact',
                    'Responsible person\'s details (phone, e-mail)',
                    'responsible_person_contact'
                ),
            ])
            ->columns(3)
            ->collapsible()
            ->hiddenOn('create');
    }

    /**
     * One submitted value, plus a marker when staff have since changed the live record —
     * so a reviewer can tell at a glance what was corrected and what came in that way.
     *
     * @param  string  $key  key inside the submitted_data snapshot
     * @param  string|null  $liveColumn  column the snapshot value maps to, when it differs
     */
    protected static function submitted(string $key, string $label, ?string $liveColumn = null): Placeholder
    {
        return Placeholder::make('submitted_'.$key)
            ->label($label)
            ->content(function (Ngo $record) use ($key, $liveColumn) {
                $submitted = $record->submitted_data[$key] ?? null;

                if (is_array($submitted)) {
                    $submitted = implode(', ', $submitted);
                }

                if (blank($submitted)) {
                    return '—';
                }

                $html = nl2br(e($submitted));

                $live = $record->{$liveColumn ?? $key};

                if (is_array($live)) {
                    $live = implode(', ', $live);
                }

                if (filled($live) && (string) $live !== (string) $submitted) {
                    $html .= '<div style="font-size:11px;color:#b45309;margin-top:2px;">Edited by staff &rarr; '.e($live).'</div>';
                }

                return new HtmlString($html);
            });
    }

    /**
     * Only what actually appears on the public directory profile — nothing else, so a
     * reviewer knows every field here is going live.
     */
    protected static function publicProfile(): Section
    {
        return Section::make('Public profile')
            ->description('Everything here appears on the public directory page.')
            ->icon('heroicon-o-globe-alt')
            ->disabled(fn (?Ngo $record) => $record?->isClosed() ?? false)
            ->schema([
                Placeholder::make('translation_status')
                    ->hiddenLabel()
                    ->content(function (?Ngo $record) {
                        if (! $record) {
                            return '';
                        }

                        $missing = [];
                        foreach (['en' => 'English', 'sq' => 'Shqip'] as $code => $label) {
                            if (! $record->getTranslation('name', $code, false)) {
                                $missing[] = $label;
                            }
                        }

                        if (empty($missing)) {
                            return new HtmlString('<div style="padding:8px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#166534;font-size:13px;">All translations complete</div>');
                        }

                        return new HtmlString('<div style="padding:8px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#991b1b;font-size:13px;">Missing translations: <strong>'.implode(', ', $missing).'</strong></div>');
                    })
                    ->columnSpanFull()
                    ->hiddenOn('create'),

                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name.en')->label('Name (EN)')->required(),
                                Textarea::make('description.en')->label('Description (EN)')->rows(4),
                            ]),
                        Tab::make('Shqip')
                            ->schema([
                                TextInput::make('name.sq')->label('Name (SQ)'),
                                Textarea::make('description.sq')->label('Description (SQ)')->rows(4),
                            ]),
                    ])->columnSpanFull(),

                FileUpload::make('logo')->image()->disk('public')->directory('ngos'),
                TextInput::make('slug')
                    ->required()
                    ->helperText('The address of the public page.'),
                TextInput::make('contact_email')
                    ->email()
                    ->helperText('Take it from the submitted details above.'),
                TextInput::make('contact_phone')->tel(),
                TextInput::make('location')->helperText('City or municipality. Used by directory search.'),
                TextInput::make('website')->url()->prefix('https://'),
                TextInput::make('category')
                    ->label('Area of activity')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    /**
     * Held on file, never published. Collapsed because it is usually correct as submitted.
     */
    protected static function registryRecord(): Section
    {
        return Section::make('Registry record')
            ->description('Held on file for correspondence and verification. Not published.')
            ->icon('heroicon-o-identification')
            ->collapsed()
            ->collapsible()
            ->disabled(fn (?Ngo $record) => $record?->isClosed() ?? false)
            ->schema([
                TextInput::make('abbreviation'),
                TextInput::make('registration_number')->unique(ignoreRecord: true),
                TextInput::make('fiscal_number'),
                TextInput::make('responsible_person'),
                Select::make('primary_community')
                    ->options(array_combine(RegisterController::COMMUNITIES, RegisterController::COMMUNITIES)),
                Select::make('additional_communities')
                    ->multiple()
                    ->options(array_combine(RegisterController::COMMUNITIES, RegisterController::COMMUNITIES)),
            ])
            ->columns(3);
    }

    protected static function internalNotes(): Section
    {
        return Section::make('Internal review notes')
            ->description('Never shown to the organization. The full trail is in the decision history below.')
            ->icon('heroicon-o-lock-closed')
            ->collapsed(fn (?Ngo $record) => blank($record?->review_notes))
            ->collapsible()
            ->disabled(fn (?Ngo $record) => $record?->isClosed() ?? false)
            ->schema([
                Textarea::make('review_notes')
                    ->hiddenLabel()
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->hiddenOn('create');
    }
}
