<?php

namespace App\Services;

use App\Enums\HousekeepingTaskStatus;
use App\Enums\RoomStatus;
use App\Models\HousekeepingTask;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class HousekeepingTaskService
{
    /**
     * Assign a task to a user.
     */
    public function assign(HousekeepingTask $task, User $user, User $actor): void
    {
        if (!in_array($task->status, [HousekeepingTaskStatus::PENDING, HousekeepingTaskStatus::ASSIGNED])) {
            throw new Exception("Only PENDING or already ASSIGNED tasks can be assigned.");
        }

        DB::transaction(function () use ($task, $user, $actor) {
            $task->update([
                'assigned_to' => $user->id,
                'status' => HousekeepingTaskStatus::ASSIGNED,
            ]);

            // We can also create activity log here, similar to ServiceRequestActivityLog
            // but we'll assume the controller handles simple logging or we keep it simple.
        });
    }

    /**
     * Start a task (In Progress).
     */
    public function start(HousekeepingTask $task, User $actor): void
    {
        if (!in_array($task->status, [HousekeepingTaskStatus::ASSIGNED, HousekeepingTaskStatus::PENDING])) {
            throw new Exception("Only ASSIGNED or PENDING tasks can be started.");
        }

        DB::transaction(function () use ($task, $actor) {
            $task->update([
                'status' => HousekeepingTaskStatus::IN_PROGRESS,
                'started_at' => now(),
            ]);

            // Update Room Status
            $task->room->update(['status' => RoomStatus::CLEANING]);
        });
    }

    /**
     * Complete a task.
     */
    public function complete(HousekeepingTask $task, User $actor): void
    {
        if ($task->status !== HousekeepingTaskStatus::IN_PROGRESS) {
            throw new Exception("Only IN_PROGRESS tasks can be completed.");
        }

        DB::transaction(function () use ($task, $actor) {
            $task->update([
                'status' => HousekeepingTaskStatus::COMPLETED,
                'completed_at' => now(),
            ]);

            // Update Room Status to INSPECTED or DIRTY until inspected?
            // Actually, wait for Inspector to make it AVAILABLE. Let's make it INSPECTED temporarily.
            $task->room->update(['status' => RoomStatus::INSPECTED]);
        });
    }

    /**
     * Inspect and finalize a task.
     */
    public function inspect(HousekeepingTask $task, User $actor): void
    {
        if ($task->status !== HousekeepingTaskStatus::COMPLETED) {
            throw new Exception("Only COMPLETED tasks can be inspected.");
        }

        DB::transaction(function () use ($task, $actor) {
            $task->update([
                'status' => HousekeepingTaskStatus::INSPECTED,
                'inspected_at' => now(),
                'inspected_by' => $actor->id,
            ]);

            // Room is now AVAILABLE
            $task->room->update(['status' => RoomStatus::AVAILABLE]);
        });
    }
}
