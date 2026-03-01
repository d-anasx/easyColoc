<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;

class ExpensePolicy
{
    public function view(User $user, Expense $expense): bool
    {
        return $user->hasActiveColocation() && $expense->colocation->members->contains($user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasActiveColocation();
    }
}