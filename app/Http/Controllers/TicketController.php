<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\CannedResponse;
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

        $cannedResponses = [];
        if (in_array(auth()->user()->role, ['it_admin', 'it_support'])) {
            $cannedResponses = CannedResponse::orderBy('title')->get();
        }

        return view('tickets.show', compact('ticket', 'cannedResponses'));
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

    public function rate(Request $request, Ticket $ticket)
    {
        Gate::authorize('view', $ticket); // Ensure they own the ticket or can view it

        if ($ticket->status !== 'CLOSED' || $ticket->user_id !== $request->user()->id) {
            abort(403, 'Only the ticket creator can rate a closed ticket.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $ticket->update($validated);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function exportCsv(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);

        $tickets = Ticket::with(['category', 'user', 'assignedUser'])->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=tickets_'.date('Y-m-d').'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Ticket Number', 'Title', 'Status', 'Priority', 'Category', 'Created By', 'Assigned To', 'Created At'];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->title,
                    $ticket->status,
                    $ticket->priority,
                    $ticket->category->name ?? 'N/A',
                    $ticket->user->name ?? 'N/A',
                    $ticket->assignedUser->name ?? 'N/A',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
