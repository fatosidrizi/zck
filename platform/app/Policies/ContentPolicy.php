<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Shared rules for public content (news, public calls, events, communities, NGO profiles).
 * Every staff member can write and publish; only admins can remove something for good.
 */
abstract class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Model $model): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Model $model): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
