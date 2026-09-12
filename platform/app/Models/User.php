<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isStaff();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role->isSuperAdmin();
    }

    /**
     * Admin or super admin: the people who decide cases.
     */
    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function isStaff(): bool
    {
        return $this->role->isStaff();
    }

    /**
     * Users who can be put in charge of a discrimination report — the same set that
     * can open one, so a report is never assigned to someone who cannot see it.
     */
    public function scopeReportHandlers(Builder $query): Builder
    {
        return $query->whereIn('role', UserRole::adminValues());
    }
}
