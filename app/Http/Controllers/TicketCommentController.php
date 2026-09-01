<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketCommentController extends Controller
{
    public function index(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $comments = $ticket->comments()->with('user')->orderBy('created_at', 'asc')->get();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('tickets.partials.comment', compact('comment'))->render();
        }

        return response()->json(['html' => $html]);
    }

    public function store(Request $request, Ticket $ticket)
    {
        Gate::authorize('view', $ticket); // If they can view it, they can comment on it (for now)

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Comment added.');
    }
}
