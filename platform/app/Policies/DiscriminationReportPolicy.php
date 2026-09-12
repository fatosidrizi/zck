<?php

namespace App\Policies;

use App\Models\DiscriminationReport;
use App\Models\User;

/**
 * Reports hold personal data about reporters and alleged incidents. Only admins handle
 * them, and only a super admin can erase one.
 */
class DiscriminationReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, DiscriminationReport $report): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, DiscriminationReport $report): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, DiscriminationReport $report): bool
    {
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
