<?php

namespace App\Filament\Widgets;

use App\Models\Community;
use App\Models\Event;
use App\Models\News;
use App\Models\Ngo;
use App\Models\PublicCall;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TranslationStatus extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): string
    {
        return 'Translation Status (SQ)';
    }

    protected function getStats(): array
    {
        $models = [
            'News' => [News::class, 'title'],
            'Public Calls' => [PublicCall::class, 'title'],
            'NGOs' => [Ngo::class, 'name'],
            'Communities' => [Community::class, 'name'],
            'Events' => [Event::class, 'title'],
        ];

        $stats = [];
        foreach ($models as $label => [$class, $field]) {
            $total = $class::count();
            $translated = $class::whereNotNull("{$field}->sq")->where("{$field}->sq", '!=', '')->count();
            $missing = $total - $translated;
            $stats[] = Stat::make($label, $total > 0 ? round(($translated / $total) * 100) . '%' : '—')
                ->description($missing > 0 ? $missing . ' missing' : 'All done')
                ->color($missing > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}
