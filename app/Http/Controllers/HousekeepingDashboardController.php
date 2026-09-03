<?php

namespace App\Http\Controllers;

use App\Enums\HousekeepingTaskStatus;
use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class HousekeepingDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // Admin sees all, Housekeeping staff sees their assignments or all pending
        $query = HousekeepingTask::query();

        if ($user->role === 'housekeeping') {
            // Staff can see tasks assigned to them, or unassigned tasks?
            // The prompt says "view housekeeping dashboard", let's give them a general overview too.
            // But highlight their tasks.
        }

        $totalTasks = HousekeepingTask::count();
        $pending = HousekeepingTask::where('status', HousekeepingTaskStatus::PENDING)->count();
        $assigned = HousekeepingTask::where('status', HousekeepingTaskStatus::ASSIGNED)->count();
        $inProgress = HousekeepingTask::where('status', HousekeepingTaskStatus::IN_PROGRESS)->count();
        $completed = HousekeepingTask::where('status', HousekeepingTaskStatus::COMPLETED)->count();
        
        $myTasks = HousekeepingTask::where('assigned_to', $user->id)
            ->whereNotIn('status', [HousekeepingTaskStatus::INSPECTED, HousekeepingTaskStatus::CANCELLED])
            ->with(['room.roomType'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $roomsRequiringAttention = Room::whereIn('status', [
            \App\Enums\RoomStatus::DIRTY, 
            \App\Enums\RoomStatus::CLEANING, 
            \App\Enums\RoomStatus::INSPECTED
        ])->with(['roomType', 'housekeepingTasks' => function($q) {
            $q->latest()->limit(1);
        }])->get();

        return view('housekeeping.dashboard', compact(
            'totalTasks', 'pending', 'assigned', 'inProgress', 'completed',
            'myTasks', 'roomsRequiringAttention'
        ));
    }
}
