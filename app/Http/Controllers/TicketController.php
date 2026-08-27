<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);
        $user = $request->user();

        $query = Ticket::with(['category', 'assignedUser']);

        if ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'it_support') {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhereNull('assigned_to');
            });
        }

        $tickets = $query->latest()->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        Gate::authorize('create', Ticket::class);
        $categories = Category::where('is_active', true)->get();

        return view('tickets.create', compact('categories'));
    }

    public function store(StoreTicketRequest $request)
    {
        $validated = $request->validated();

        $ticket_number = 'IT-'.date('Y').'-'.str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $ticket = Ticket::create(array_merge($validated, [
            'ticket_number' => $ticket_number,
            'user_id' => $request->user()->id,
            'department_id' => $request->user()->department_id,
            'status' => 'OPEN',
        ]));

        // Notify Admins
        $admins = User::where('role', 'it_admin')->get();
        Notification::send($admins, new NewTicketNotification($ticket));

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);
        $ticket->load(['user', 'department', 'category', 'assignedUser', 'comments.user', 'activityLogs']);

        return view('tickets.show', compact('ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        if ($validated['status'] === 'RESOLVED') {
            Gate::authorize('resolve', $ticket);
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] === 'CLOSED') {
            Gate::authorize('close', $ticket);
            $validated['closed_at'] = now();
        } else {
            Gate::authorize('update', $ticket);
        }

        $ticket->update($validated);

        // Notify Ticket Creator
        $ticket->user->notify(new TicketStatusUpdatedNotification($ticket));

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }
}
