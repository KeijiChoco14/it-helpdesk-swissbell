<?php

namespace App\Policies;

use App\Models\HousekeepingTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HousekeepingTaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role, ['it_admin', 'it_support', 'housekeeping']);
    }

    public function view(User $user, HousekeepingTask $housekeepingTask)
    {
        if (in_array($user->role, ['it_admin', 'it_support'])) {
            return true;
        }

        if ($user->role === 'housekeeping') {
            return true; // Housekeeping can view all tasks, e.g. to see what needs done or who's on it
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->role === 'it_admin' || $user->role === 'housekeeping'; // Maybe housekeeping supervisor can create
    }

    public function update(User $user, HousekeepingTask $housekeepingTask)
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        if ($user->role === 'housekeeping' && $housekeepingTask->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, HousekeepingTask $housekeepingTask)
    {
        if ($user->role === 'it_admin') {
            // Only PENDING tasks can be deleted
            return $housekeepingTask->status->value === 'PENDING';
        }

        return false;
    }
}
