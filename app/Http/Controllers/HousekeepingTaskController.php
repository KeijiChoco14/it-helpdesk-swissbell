<?php

namespace App\Http\Controllers;

use App\Enums\HousekeepingTaskStatus;
use App\Enums\HousekeepingTaskType;
use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\User;
use App\Services\HousekeepingTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HousekeepingTaskController extends Controller
{
    protected $service;

    public function __construct(HousekeepingTaskService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', HousekeepingTask::class);

        $query = HousekeepingTask::with(['room', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->task_type);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);
        $statuses = HousekeepingTaskStatus::cases();
        $types = HousekeepingTaskType::cases();
        $staff = User::whereIn('role', ['housekeeping'])->get(); // IT Admins can also be assigned if needed, but let's stick to housekeeping

        return view('housekeeping.tasks.index', compact('tasks', 'statuses', 'types', 'staff'));
    }

    public function create()
    {
        Gate::authorize('create', HousekeepingTask::class);

        $rooms = Room::orderBy('room_number')->get();
        $types = HousekeepingTaskType::cases();
        $staff = User::whereIn('role', ['housekeeping'])->get();

        return view('housekeeping.tasks.create', compact('rooms', 'types', 'staff'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', HousekeepingTask::class);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'task_type' => 'required|string',
            'priority' => 'required|string|in:Low,Medium,High,Critical',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $task = HousekeepingTask::create([
            'room_id' => $validated['room_id'],
            'task_type' => $validated['task_type'],
            'priority' => $validated['priority'],
            'status' => HousekeepingTaskStatus::PENDING,
            'scheduled_at' => $validated['scheduled_at'],
            'notes' => $validated['notes'],
        ]);

        if (!empty($validated['assigned_to'])) {
            $user = User::find($validated['assigned_to']);
            $this->service->assign($task, $user, $request->user());
        }

        return redirect()->route('housekeeping.tasks.show', $task)->with('success', 'Housekeeping task created successfully.');
    }

    public function show(HousekeepingTask $housekeepingTask)
    {
        Gate::authorize('view', $housekeepingTask);

        $housekeepingTask->load(['room.roomType', 'assignee', 'inspector']);
        $staff = User::whereIn('role', ['housekeeping'])->get();

        return view('housekeeping.tasks.show', compact('housekeepingTask', 'staff'));
    }

    public function assign(Request $request, HousekeepingTask $housekeepingTask)
    {
        Gate::authorize('update', $housekeepingTask);

        $request->validate(['assigned_to' => 'required|exists:users,id']);
        
        $user = User::findOrFail($request->assigned_to);
        
        try {
            $this->service->assign($housekeepingTask, $user, $request->user());
            return back()->with('success', 'Task assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function start(Request $request, HousekeepingTask $housekeepingTask)
    {
        Gate::authorize('update', $housekeepingTask);

        try {
            $this->service->start($housekeepingTask, $request->user());
            return back()->with('success', 'Task started successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function complete(Request $request, HousekeepingTask $housekeepingTask)
    {
        Gate::authorize('update', $housekeepingTask);

        try {
            $this->service->complete($housekeepingTask, $request->user());
            return back()->with('success', 'Task marked as completed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function inspect(Request $request, HousekeepingTask $housekeepingTask)
    {
        // Typically only admins or supervisors can inspect
        if ($request->user()->role !== 'it_admin') { // Adapt if housekeeping supervisor role exists
            abort(403, 'Only admins can inspect tasks for now.');
        }

        try {
            $this->service->inspect($housekeepingTask, $request->user());
            return back()->with('success', 'Task inspected and room marked as Available.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
