<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketAssignmentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        Gate::authorize('assign', $ticket);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => $ticket->status === 'OPEN' ? 'IN_PROGRESS' : $ticket->status,
        ]);

        // Notify Assigned User
        if ($ticket->assignedUser) {
            $ticket->assignedUser->notify(new TicketAssignedNotification($ticket));
        }

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }
}
