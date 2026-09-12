<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Four roles, three of which see the admin panel. Each tier adds one responsibility:
 * editors publish content, admins decide cases, super admins manage accounts.
 * Public accounts (User) exist only to sign in on the website.
 */
enum UserRole: string implements HasColor, HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Editor = 'editor';
    case User = 'user';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super admin',
            self::Admin => 'Admin',
            self::Editor => 'Editor',
            self::User => 'User',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'danger',
            self::Admin => 'warning',
            self::Editor => 'info',
            self::User => 'gray',
        };
    }

    /**
     * Can sign in to the admin panel and publish content.
     */
    public function isStaff(): bool
    {
        return $this !== self::User;
    }

    /**
     * Decides cases: NGO applications and discrimination reports.
     */
    public function isAdmin(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Admin], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this === self::SuperAdmin;
    }

    /** @return list<string> */
    public static function staffValues(): array
    {
        return array_map(fn (self $role) => $role->value, array_filter(self::cases(), fn (self $role) => $role->isStaff()));
    }

    /** @return list<string> */
    public static function adminValues(): array
    {
        return array_map(fn (self $role) => $role->value, array_filter(self::cases(), fn (self $role) => $role->isAdmin()));
    }
}
