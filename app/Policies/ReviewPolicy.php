<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Review $review)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Review $review)
    {
        return $user->role === 'admin';
    }
}
