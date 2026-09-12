<?php

namespace App\Policies;

use App\Models\User;

/**
 * Accounts and roles are super admin territory. A super admin cannot delete their own
 * account from the panel, so the office can never lock itself out.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isSuperAdmin() && ! $user->is($model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
