<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoStatusHistory extends Model
{
    protected $fillable = [
        'ngo_id', 'event', 'old_status', 'new_status', 'note', 'changed_by',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(Ngo::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
