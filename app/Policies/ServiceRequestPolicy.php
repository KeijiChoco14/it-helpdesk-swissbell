<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }
        if ($user->role === 'it_support') {
            return true;
        } // Or restrict to assigned

        return $user->id === $serviceRequest->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }
        if ($user->role === 'it_support' && $serviceRequest->assigned_to === $user->id) {
            return true;
        }

        return $user->id === $serviceRequest->user_id;
    }

    public function delete(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->role === 'it_admin';
    }

    public function restore(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->role === 'it_admin';
    }

    public function forceDelete(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->role === 'it_admin';
    }

    public function assign(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->role === 'it_admin';
    }

    public function resolve(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->role === 'it_support' && $serviceRequest->assigned_to === $user->id;
    }

    public function close(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->id === $serviceRequest->user_id;
    }

    public function reopen(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->id === $serviceRequest->user_id;
    }
}
