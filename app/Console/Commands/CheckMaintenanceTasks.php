<?php

namespace App\Console\Commands;

use App\Models\MaintenanceTask;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-maintenance-tasks')]
#[Description('Checks for due maintenance tasks and generates tickets')]
class CheckMaintenanceTasks extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dueTasks = MaintenanceTask::where(function ($query) {
            $query->whereNull('next_run_at')
                ->orWhere('next_run_at', '<=', now());
        })->get();

        $count = 0;
        foreach ($dueTasks as $task) {
            $ticketNumber = 'IT-'.date('Y').'-'.str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

            // Assume the system creates it, or assign to the task's assignee
            // For now, let's just make it a generic maintenance ticket
            $admin = User::where('role', 'it_admin')->first();

            Ticket::create([
                'title' => '[MAINTENANCE] '.$task->title,
                'description' => $task->description ?? 'Scheduled maintenance task.',
                'ticket_number' => $ticketNumber,
                'status' => 'OPEN',
                'priority' => 'Medium',
                'user_id' => $admin ? $admin->id : 1,
                'assigned_to' => $task->assigned_to,
                // Hardcode category or department if needed, for simplicity we leave null or fetch default
            ]);

            $task->update([
                'last_run_at' => now(),
                'next_run_at' => now()->addDays($task->frequency_days),
            ]);

            $count++;
        }

        $this->info("Generated $count maintenance tickets.");
    }
}
