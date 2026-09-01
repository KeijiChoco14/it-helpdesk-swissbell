<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('equipment.index') }}" class="p-2 rounded-xl hover:bg-slate-200/50 text-slate-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Equipment Details</h1>
        </div>
        
        @can('update', $equipment)
            <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                Edit Equipment
            </a>
        @endcan
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Device Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $equipment->name }}</h2>
                        <p class="text-sm text-slate-500 mt-1 uppercase tracking-wider">{{ $equipment->type }}</p>
                    </div>
                    <span class="badge {{ $equipment->status === 'active' ? 'badge-resolved' : ($equipment->status === 'maintenance' ? 'badge-in-progress' : 'badge-closed') }}">
                        {{ ucfirst($equipment->status) }}
                    </span>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Assigned To</p>
                        @if($equipment->user)
                            <div class="flex items-center gap-2 mt-1.5">
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">{{ strtoupper(substr($equipment->user->name, 0, 1)) }}</div>
                                <span class="text-sm font-medium text-slate-800">{{ $equipment->user->name }}</span>
                            </div>
                        @else
                            <p class="text-sm text-slate-600 mt-1 italic">Unassigned</p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Brand / Model</p>
                        <p class="text-sm text-slate-800 mt-1">{{ $equipment->brand ?? '-' }} {{ $equipment->model ?? '' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Serial Number</p>
                        <p class="text-sm font-mono text-slate-800 mt-1">{{ $equipment->serial_number ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Location</p>
                        <p class="text-sm text-slate-800 mt-1">{{ $equipment->location ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Purchase Date</p>
                        <p class="text-sm text-slate-800 mt-1">{{ $equipment->purchase_date ? $equipment->purchase_date->format('M d, Y') : '-' }}</p>
                    </div>
                </div>

                @can('delete', $equipment)
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this equipment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost w-full text-red-600 hover:bg-red-50 hover:text-red-700 justify-center">
                                Delete Equipment
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            @can('update', $equipment)
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Manage Assignment</h3>
                @if($equipment->user_id)
                    <form action="{{ route('equipment.return', $equipment) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-full justify-center">
                            Return Equipment
                        </button>
                    </form>
                    <div class="my-4 text-center text-xs text-slate-400 uppercase font-semibold">Or Reassign</div>
                @endif
                
                <form action="{{ route('equipment.assign', $equipment) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Assign To</label>
                            <select name="user_id" class="input" required>
                                <option value="">Select User...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $equipment->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->department->name ?? 'No Dept' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Notes (Optional)</label>
                            <input type="text" name="notes" class="input" placeholder="e.g. For new project">
                        </div>
                        <button type="submit" class="btn btn-primary w-full justify-center">
                            {{ $equipment->user_id ? 'Reassign' : 'Assign Equipment' }}
                        </button>
                    </div>
                </form>
            </div>
            @endcan
        </div>

        <!-- Assignment & Cleaning History -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Assignment History</h3>
                <div class="space-y-4">
                    @forelse($equipment->assignments as $assignment)
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($assignment->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">{{ $assignment->user->name }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Assigned: {{ $assignment->assigned_at->format('M d, Y H:i') }}
                                            @if($assignment->returned_at)
                                                <span class="mx-1">•</span> Returned: {{ $assignment->returned_at->format('M d, Y H:i') }}
                                            @else
                                                <span class="mx-1">•</span> <span class="text-emerald-600 font-semibold">Currently Using</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if($assignment->notes)
                                <p class="text-sm text-slate-600 mt-3 pl-11">{{ $assignment->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-sm text-slate-500">No assignment history.</div>
                    @endforelse
                </div>
            </div>
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Maintenance & Cleaning History</h3>
                    @can('create', App\Models\CleaningTask::class)
                        <a href="{{ route('cleaning-tasks.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-ghost text-red-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            New Task
                        </a>
                    @endcan
                </div>

                <div class="space-y-4">
                    @forelse($equipment->cleaningTasks()->latest()->get() as $task)
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-800">{{ str_replace('_', ' ', ucwords($task->task_type)) }}</h4>
                                        <span class="badge {{ $task->status === 'completed' ? 'badge-resolved' : ($task->status === 'in_progress' ? 'badge-in-progress' : 'badge-open') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 mt-1">{{ $task->description ?? 'No description provided.' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500">{{ $task->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-100/60">
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $task->performer->name }}
                                </div>
                                @if($task->completed_at)
                                    <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        Completed on {{ $task->completed_at->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-500">No maintenance history recorded for this device.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
