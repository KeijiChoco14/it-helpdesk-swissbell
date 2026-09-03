<?php

namespace Database\Seeders;

use App\Enums\HousekeepingTaskStatus;
use App\Enums\HousekeepingTaskType;
use App\Enums\RoomStatus;
use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class HousekeepingTaskSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        $staff = User::where('role', 'housekeeping')->get();
        $admin = User::where('role', 'it_admin')->first();
        
        if ($rooms->isEmpty() || $staff->isEmpty()) {
            return; // Requires rooms and housekeeping staff
        }

        $types = HousekeepingTaskType::cases();
        $priorities = ['Low', 'Medium', 'High', 'Critical'];

        // Let's create about 30 tasks
        for ($i = 1; $i <= 30; $i++) {
            $room = $rooms->random();
            $assignedStaff = rand(0, 1) ? $staff->random() : null; // Some unassigned
            $status = HousekeepingTaskStatus::PENDING;
            
            if ($assignedStaff) {
                // If assigned, give it a random progression status
                $status = collect([
                    HousekeepingTaskStatus::ASSIGNED,
                    HousekeepingTaskStatus::IN_PROGRESS,
                    HousekeepingTaskStatus::COMPLETED,
                    HousekeepingTaskStatus::INSPECTED
                ])->random();
            }

            $task = HousekeepingTask::create([
                'room_id' => $room->id,
                'task_type' => collect($types)->random()->value,
                'priority' => collect($priorities)->random(),
                'status' => $status,
                'assigned_to' => $assignedStaff?->id,
                'scheduled_at' => rand(0, 1) ? now()->addHours(rand(1, 48)) : null,
                'notes' => rand(0, 1) ? 'Please ensure extra towels are provided.' : null,
            ]);

            // Fake timeline based on status
            if (in_array($status, [HousekeepingTaskStatus::IN_PROGRESS, HousekeepingTaskStatus::COMPLETED, HousekeepingTaskStatus::INSPECTED])) {
                $task->started_at = now()->subMinutes(rand(60, 120));
            }
            if (in_array($status, [HousekeepingTaskStatus::COMPLETED, HousekeepingTaskStatus::INSPECTED])) {
                $task->completed_at = now()->subMinutes(rand(10, 50));
            }
            if ($status === HousekeepingTaskStatus::INSPECTED) {
                $task->inspected_at = now()->subMinutes(rand(1, 9));
                $task->inspected_by = $admin?->id;
            }
            
            $task->save();

            // Sync Room Status
            if ($status === HousekeepingTaskStatus::IN_PROGRESS) {
                $room->update(['status' => RoomStatus::CLEANING]);
            } elseif ($status === HousekeepingTaskStatus::COMPLETED) {
                $room->update(['status' => RoomStatus::INSPECTED]);
            } elseif ($status === HousekeepingTaskStatus::INSPECTED) {
                $room->update(['status' => RoomStatus::AVAILABLE]);
            }
        }
    }
}
