<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;

class ExpensePolicy
{
    public function create(User $user): bool
    {
        return $user->hasActiveColocation();
    }
}