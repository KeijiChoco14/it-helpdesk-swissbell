<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-ticket-s-l-a')]
#[Description('Command description')]
class CheckTicketSLA extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueTickets = \App\Models\Ticket::where('is_escalated', false)
            ->whereNotIn('status', ['RESOLVED', 'CLOSED'])
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->get();

        if ($overdueTickets->isEmpty()) {
            $this->info('No overdue tickets found.');
            return;
        }

        $admins = \App\Models\User::where('role', 'it_admin')->get();

        foreach ($overdueTickets as $ticket) {
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\TicketEscalated($ticket));
            $ticket->update(['is_escalated' => true]);
            $this->info("Escalated ticket: {$ticket->ticket_number}");
        }

        $this->info("Escalation check complete.");
    }
}
