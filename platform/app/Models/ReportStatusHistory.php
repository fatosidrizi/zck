<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatusHistory extends Model
{
    protected $fillable = [
        'discrimination_report_id', 'old_status', 'new_status', 'note', 'changed_by',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(DiscriminationReport::class, 'discrimination_report_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
