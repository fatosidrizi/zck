<?php

namespace App\Filament\Resources\Ngos\Pages;

use App\Filament\Resources\Ngos\NgoResource;
use App\Models\Ngo;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNgos extends ListRecords
{
    protected static string $resource = NgoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * Applications waiting on a decision come first — that is the work queue.
     */
    public function getTabs(): array
    {
        return [
            'pending' => Tab::make(__('Pending review'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(Ngo::where('status', 'pending')->count())
                ->badgeColor('warning'),

            'published' => Tab::make(__('Published'))
                ->modifyQueryUsing(fn (Builder $query) => $query->published()),

            'approved_unpublished' => Tab::make(__('Approved, not published'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved')->where('is_active', false)),

            'closed' => Tab::make(__('Closed'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),

            'all' => Tab::make(__('All')),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        // Land on the queue only when there is something in it.
        return Ngo::where('status', 'pending')->exists() ? 'pending' : 'all';
    }
}
