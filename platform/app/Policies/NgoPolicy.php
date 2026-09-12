<?php

namespace App\Policies;

use App\Models\Ngo;
use App\Models\User;

/**
 * The NGO profile is content, so editors may maintain it. The application decision
 * (approve, reject, reopen, publish, unpublish) is an admin's call.
 */
class NgoPolicy extends ContentPolicy
{
    public function review(User $user, Ngo $ngo): bool
    {
        return $user->isAdmin();
    }
}
