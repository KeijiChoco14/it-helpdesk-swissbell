<?php

namespace App\Policies;

use App\Models\RoomType;
use App\Models\User;

class RoomTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'it_admin' || $user->role === 'it_support';
    }

    public function view(User $user, RoomType $roomType): bool
    {
        return $user->role === 'it_admin' || $user->role === 'it_support';
    }

    public function create(User $user): bool
    {
        return $user->role === 'it_admin';
    }

    public function update(User $user, RoomType $roomType): bool
    {
        return $user->role === 'it_admin';
    }

    public function delete(User $user, RoomType $roomType): bool
    {
        return $user->role === 'it_admin';
    }
}
