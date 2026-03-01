<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryPolicy
{
    use AuthorizesRequests;
    public function manage(User $user, Category $category): bool
    {
        $member = $category->colocation->members->find($user->id);
        return $member && $member->pivot->role === 'owner';
    }
}