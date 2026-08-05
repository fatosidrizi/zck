<?php

namespace App\Filament\Resources\DiscriminationReports\Pages;

use App\Filament\Resources\DiscriminationReports\DiscriminationReportResource;
use App\Models\DiscriminationReport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListDiscriminationReports extends ListRecords
{
    protected static string $resource = DiscriminationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Export CSV')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, [
                            'Tracking Code', 'Reporter Name', 'Email', 'Phone',
                            'Type', 'Description', 'Location', 'Incident Date',
                            'Status', 'Assigned To', 'Admin Notes', 'Submitted At',
                        ]);

                        DiscriminationReport::with('assignedUser')
                            ->orderByDesc('created_at')
                            ->each(function ($report) use ($handle) {
                                fputcsv($handle, [
                                    $report->tracking_code,
                                    $report->reporter_name,
                                    $report->reporter_email,
                                    $report->reporter_phone,
                                    $report->type,
                                    $report->description,
                                    $report->location,
                                    $report->incident_date?->format('Y-m-d'),
                                    $report->status,
                                    $report->assignedUser?->name,
                                    $report->admin_notes,
                                    $report->created_at->format('Y-m-d H:i'),
                                ]);
                            });

                        fclose($handle);
                    }, 'discrimination-reports-' . now()->format('Y-m-d') . '.csv');
                }),
            CreateAction::make(),
        ];
    }
}
