<?php

namespace App\Filament\Widgets;

use App\Models\Community;
use App\Models\DiscriminationReport;
use App\Models\Event;
use App\Models\News;
use App\Models\Ngo;
use App\Models\PublicCall;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newReports = DiscriminationReport::where('status', 'new')->count();

        return [
            Stat::make('Total News', News::count())
                ->description(News::where('status', 'draft')->count() . ' drafts')
                ->color('primary')
                ->icon('heroicon-o-newspaper'),
            Stat::make('Public Calls', PublicCall::published()->count())
                ->description(PublicCall::published()->where('deadline', '>=', now())->count() . ' open')
                ->color('success')
                ->icon('heroicon-o-megaphone'),
            Stat::make('NGOs', Ngo::where('is_active', true)->count())
                ->description(Ngo::where('is_active', false)->count() . ' pending approval')
                ->color('warning')
                ->icon('heroicon-o-building-office'),
            Stat::make('Reports', DiscriminationReport::count())
                ->description($newReports . ' new')
                ->color($newReports > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),
            Stat::make('Communities', Community::count())
                ->icon('heroicon-o-user-group'),
            Stat::make('Events', Event::count())
                ->description(Event::where('event_date', '>=', now())->count() . ' upcoming')
                ->color('info')
                ->icon('heroicon-o-calendar'),
        ];
    }
}
