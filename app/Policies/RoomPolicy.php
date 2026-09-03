<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'it_admin' || $user->role === 'it_support';
    }

    public function view(User $user, Room $room): bool
    {
        return true; // Employees might need to view room info in request context
    }

    public function create(User $user): bool
    {
        return $user->role === 'it_admin';
    }

    public function update(User $user, Room $room): bool
    {
        return $user->role === 'it_admin';
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->role === 'it_admin';
    }
}
