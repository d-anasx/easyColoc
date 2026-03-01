<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Colocation;

class ColocationPolicy
{

    public function invite(User $user, Colocation $colocation): bool
    {
        return $this->isOwner($user, $colocation);
    }

    public function removeMember(User $user, Colocation $colocation): bool
    {
        return $this->isOwner($user, $colocation);
    }

    private function isOwner(User $user, Colocation $colocation): bool
    {
        $member = $colocation->members->find($user->id);
        return $member && $member->pivot->role === 'owner';
    }
}