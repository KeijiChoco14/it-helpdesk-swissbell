<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }
        if ($user->role === 'it_support') {
            return true;
        } // Or restrict to assigned

        return $user->id === $ticket->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }
        if ($user->role === 'it_support' && $ticket->assigned_to === $user->id) {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'it_admin';
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->role === 'it_admin';
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'it_admin';
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->role === 'it_admin';
    }

    public function resolve(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->role === 'it_support' && $ticket->assigned_to === $user->id;
    }

    public function close(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    public function reopen(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'it_admin') {
            return true;
        }

        return $user->id === $ticket->user_id;
    }
}
