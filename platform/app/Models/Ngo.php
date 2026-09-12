<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Ngo extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'reference_number', 'name', 'abbreviation', 'slug', 'registration_number', 'fiscal_number',
        'description', 'logo', 'contact_email', 'contact_phone', 'responsible_person',
        'responsible_person_contact', 'website', 'location', 'category', 'primary_community',
        'additional_communities', 'is_active', 'status', 'review_notes', 'submitted_data',
        'declared_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'additional_communities' => 'array',
            'submitted_data' => 'array',
            'declared_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Only NGOs an admin has approved and left published are visible publicly.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'approved')->where('is_active', true);
    }

    /**
     * Communities the NGO works with, primary first, without duplicates.
     */
    public function allCommunities(): array
    {
        return array_values(array_unique(array_filter(
            array_merge([$this->primary_community], $this->additional_communities ?? [])
        )));
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(NgoStatusHistory::class)->orderByDesc('created_at');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * A rejected application is a closed case: read-only, and the only way out is a
     * deliberate reopen that sends it back through review.
     */
    public function isClosed(): bool
    {
        return $this->status === 'rejected';
    }

    public function wasSelfRegistered(): bool
    {
        return $this->submitted_at !== null;
    }

    /**
     * Reversing another reviewer's rejection is not routine work.
     */
    public function canBeReopenedBy(?User $user): bool
    {
        return $this->isClosed()
            && $user !== null
            && $user->can('review', $this);
    }

    /**
     * Approving is one decision, so it sets both flags at once — a reviewer can never
     * leave an NGO approved-but-invisible by forgetting a second control.
     */
    public function approve(?string $note = null): void
    {
        $this->transitionTo('approved', 'approved', $note, isActive: true);
    }

    public function reject(string $reason): void
    {
        $this->transitionTo('rejected', 'rejected', $reason, isActive: false);
    }

    /**
     * Reopening never restores the previous decision — it returns the application to the
     * queue so it has to be reviewed again on its own merits.
     */
    public function reopen(string $justification): void
    {
        $this->transitionTo('pending', 'reopened', $justification, isActive: false);
    }

    /**
     * Take an approved NGO off the directory without rejecting the application.
     */
    public function unpublish(?string $note = null): void
    {
        $this->transitionTo($this->status, 'unpublished', $note, isActive: false);
    }

    public function republish(?string $note = null): void
    {
        $this->transitionTo($this->status, 'republished', $note, isActive: true);
    }

    /**
     * The single write path for status and visibility, so nothing changes without
     * leaving a trail of who did it, when, and why.
     */
    protected function transitionTo(string $status, string $event, ?string $note, bool $isActive): void
    {
        $previousStatus = $this->status;

        $this->forceFill([
            'status' => $status,
            'is_active' => $isActive,
            // Keep the latest reason handy on the record; the history keeps all of them.
            'review_notes' => $note ?? $this->review_notes,
        ])->save();

        $this->recordEvent($event, $previousStatus, $note);
    }

    public function recordEvent(string $event, ?string $oldStatus, ?string $note = null, ?int $changedBy = null): NgoStatusHistory
    {
        return $this->statusHistories()->create([
            'event' => $event,
            'old_status' => $oldStatus,
            'new_status' => $this->status,
            'note' => $note,
            'changed_by' => $changedBy ?? auth()->id(),
        ]);
    }

    /**
     * Opens the trail. Never attributed to a user: the applicant is a member of the public,
     * and a staff member who happens to be logged in did not submit this.
     */
    public function recordSubmission(): NgoStatusHistory
    {
        return $this->statusHistories()->create([
            'event' => 'submitted',
            'old_status' => null,
            'new_status' => $this->status,
            'changed_by' => null,
        ]);
    }

    /**
     * What is still missing before this NGO would look complete in the public directory.
     * Advisory only — a reviewer can approve anyway and fill the gaps later.
     */
    public function missingForPublication(): array
    {
        $missing = [];

        if (blank($this->getTranslation('name', 'sq', false))) {
            $missing[] = __('Albanian translation of the name');
        }

        if (blank($this->getTranslation('name', 'sr', false))) {
            $missing[] = __('Serbian translation of the name');
        }

        if (blank($this->getTranslation('description', app()->getLocale(), false))) {
            $missing[] = __('Public description');
        }

        if (blank($this->contact_email) && blank($this->contact_phone)) {
            $missing[] = __('Public contact e-mail or phone');
        }

        if (blank($this->location)) {
            $missing[] = __('Location');
        }

        return $missing;
    }
}
