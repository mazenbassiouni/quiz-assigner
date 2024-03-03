<?php

namespace App\Policies;

use App\Models\User;

class RankPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }
}
