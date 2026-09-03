<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900">Housekeeping Tasks</h1>
            @can('create', App\Models\HousekeepingTask::class)
                <a href="{{ route('housekeeping.tasks.create') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New Task
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="glass-card p-4 mb-6">
        <form action="{{ route('housekeeping.tasks.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="status" class="form-label text-xs">Status</label>
                <select name="status" id="status" class="form-select py-1.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" {{ request('status') == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="task_type" class="form-label text-xs">Task Type</label>
                <select name="task_type" id="task_type" class="form-select py-1.5 text-sm">
                    <option value="">All Types</option>
                    @foreach($types as $ty)
                        <option value="{{ $ty->value }}" {{ request('task_type') == $ty->value ? 'selected' : '' }}>{{ $ty->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="assigned_to" class="form-label text-xs">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="form-select py-1.5 text-sm">
                    <option value="">All Staff</option>
                    @foreach($staff as $u)
                        <option value="{{ $u->id }}" {{ request('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary py-1.5 px-4 text-sm">Filter</button>
                @if(request()->hasAny(['status', 'task_type', 'assigned_to']))
                    <a href="{{ route('housekeeping.tasks.index') }}" class="btn btn-secondary py-1.5 px-4 text-sm">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Task #</th>
                        <th>Room</th>
                        <th>Task Type</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Scheduled</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr class="cursor-pointer hover:bg-slate-50 transition-colors" onclick="window.location='{{ route('housekeeping.tasks.show', $task) }}'">
                            <td>
                                <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $task->task_number }}</span>
                            </td>
                            <td>
                                <p class="font-bold text-slate-800">Room {{ $task->room->room_number }}</p>
                                <p class="text-xs text-slate-500">Floor {{ $task->room->floor }}</p>
                            </td>
                            <td><x-task-type-badge :type="$task->task_type" /></td>
                            <td>
                                @if($task->priority === 'Critical')
                                    <span class="text-red-600 font-bold text-xs">CRITICAL</span>
                                @elseif($task->priority === 'High')
                                    <span class="text-amber-600 font-bold text-xs">High</span>
                                @else
                                    <span class="text-slate-600 text-xs">{{ $task->priority }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($task->assignee)
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                                            {{ substr($task->assignee->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $task->assignee->name }}</span>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Unassigned</span>
                                    @endif
                                </div>
                            </td>
                            <td><x-housekeeping-status-badge :status="$task->status" /></td>
                            <td class="text-sm text-slate-500">
                                {{ $task->scheduled_at ? $task->scheduled_at->format('M d, Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">No housekeeping tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50/50">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
