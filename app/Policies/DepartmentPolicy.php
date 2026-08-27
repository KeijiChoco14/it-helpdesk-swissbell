<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'it_admin';
    }

    public function view(User $user, Department $department): bool
    {
        return $user->role === 'it_admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'it_admin';
    }

    public function update(User $user, Department $department): bool
    {
        return $user->role === 'it_admin';
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->role === 'it_admin';
    }

    public function restore(User $user, Department $department): bool
    {
        return $user->role === 'it_admin';
    }

    public function forceDelete(User $user, Department $department): bool
    {
        return $user->role === 'it_admin';
    }
}
