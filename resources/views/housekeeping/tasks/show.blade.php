<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('housekeeping.tasks.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Task {{ $housekeepingTask->task_number }}</h1>
            <x-housekeeping-status-badge :status="$housekeepingTask->status" />
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6 border-l-4 
                @if($housekeepingTask->priority === 'Critical') border-red-500
                @elseif($housekeepingTask->priority === 'High') border-amber-500
                @elseif($housekeepingTask->priority === 'Medium') border-blue-500
                @else border-slate-400 @endif
            ">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 mb-1">Room {{ $housekeepingTask->room->room_number }}</h2>
                        <p class="text-sm text-slate-500">{{ $housekeepingTask->room->roomType->name }} &bull; Floor {{ $housekeepingTask->room->floor }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500 mb-1">Task Type</p>
                        <x-task-type-badge :type="$housekeepingTask->task_type" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Priority</p>
                        <p class="font-bold text-slate-800">{{ $housekeepingTask->priority }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Scheduled</p>
                        <p class="font-medium text-slate-800">{{ $housekeepingTask->scheduled_at ? $housekeepingTask->scheduled_at->format('M d, Y H:i') : 'Not Scheduled' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Notes</p>
                    <div class="bg-slate-50 p-4 rounded text-sm text-slate-700 whitespace-pre-wrap">{{ $housekeepingTask->notes ?: 'No special instructions.' }}</div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="glass-card p-6 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Task Actions</h3>
                
                <div class="flex flex-wrap gap-3">
                    @if(in_array($housekeepingTask->status->value, ['PENDING', 'ASSIGNED']))
                        <!-- Assignment form -->
                        @can('update', $housekeepingTask)
                        <form action="{{ route('housekeeping.tasks.assign', $housekeepingTask) }}" method="POST" class="flex gap-2 w-full md:w-auto">
                            @csrf
                            <select name="assigned_to" class="form-select py-1.5 text-sm w-full md:w-48" required>
                                <option value="">Assign Staff</option>
                                @foreach($staff as $u)
                                    <option value="{{ $u->id }}" {{ $housekeepingTask->assigned_to === $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-secondary py-1.5">Assign</button>
                        </form>
                        @endcan
                    @endif

                    @can('update', $housekeepingTask)
                        @if(in_array($housekeepingTask->status->value, ['PENDING', 'ASSIGNED']))
                        <form action="{{ route('housekeeping.tasks.start', $housekeepingTask) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" {{ !$housekeepingTask->assigned_to ? 'disabled title="Assign someone first"' : '' }}>Start Task</button>
                        </form>
                        @endif

                        @if($housekeepingTask->status->value === 'IN_PROGRESS')
                        <form action="{{ route('housekeeping.tasks.complete', $housekeepingTask) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-indigo-600">Mark Completed</button>
                        </form>
                        @endif
                    @endcan

                    @if(auth()->user()->role === 'it_admin' && $housekeepingTask->status->value === 'COMPLETED')
                        <form action="{{ route('housekeeping.tasks.inspect', $housekeepingTask) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary bg-emerald-600 hover:bg-emerald-700 border-emerald-600">Pass Inspection</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar / Timeline -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Assignment</h3>
                @if($housekeepingTask->assignee)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            {{ substr($housekeepingTask->assignee->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">{{ $housekeepingTask->assignee->name }}</p>
                            <p class="text-xs text-slate-500">Housekeeping Staff</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic">No one assigned yet.</p>
                @endif
            </div>

            <div class="glass-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-400 mt-1.5"></div>
                            <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">Created</p>
                            <p class="text-xs text-slate-500">{{ $housekeepingTask->created_at->format('M d, H:i') }}</p>
                        </div>
                    </div>

                    @if($housekeepingTask->started_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1.5"></div>
                            <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">Started</p>
                            <p class="text-xs text-slate-500">{{ $housekeepingTask->started_at->format('M d, H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($housekeepingTask->completed_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 mt-1.5"></div>
                            <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">Completed</p>
                            <p class="text-xs text-slate-500">{{ $housekeepingTask->completed_at->format('M d, H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($housekeepingTask->inspected_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">Inspected</p>
                            <p class="text-xs text-slate-500">{{ $housekeepingTask->inspected_at->format('M d, H:i') }} by {{ $housekeepingTask->inspector->name ?? 'Admin' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
