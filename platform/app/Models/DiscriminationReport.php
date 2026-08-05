<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DiscriminationReport extends Model
{
    protected $fillable = [
        'tracking_code', 'reporter_name', 'reporter_email', 'reporter_phone',
        'type', 'description', 'location', 'incident_date', 'evidence_file',
        'status', 'assigned_to', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReportStatusHistory::class)->orderByDesc('created_at');
    }

    protected static function booted(): void
    {
        static::creating(function ($report) {
            if (empty($report->tracking_code)) {
                $report->tracking_code = 'ZCK-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($report) {
            if ($report->isDirty('status')) {
                ReportStatusHistory::create([
                    'discrimination_report_id' => $report->id,
                    'old_status' => $report->getOriginal('status'),
                    'new_status' => $report->status,
                    'note' => $report->admin_notes,
                    'changed_by' => auth()->id(),
                ]);
            }
        });
    }
}
