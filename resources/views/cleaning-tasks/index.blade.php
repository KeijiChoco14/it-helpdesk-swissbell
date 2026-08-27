<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Cleaning & Maintenance</h1>
    </x-slot>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">All Tasks</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage PC cleaning, thermal paste replacements, etc.</p>
            </div>
            @can('create', App\Models\CleaningTask::class)
                <a href="{{ route('cleaning-tasks.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New Task
                </a>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Task Type</th>
                        <th>Status</th>
                        <th>Performed By</th>
                        <th>Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cleaningTasks as $task)
                        <tr x-data="{ editing: false }">
                            <td>
                                <a href="{{ route('equipment.show', $task->equipment) }}" class="font-medium text-red-600 hover:text-red-800 transition-colors">
                                    {{ $task->equipment->name }}
                                </a>
                                <div class="text-xs text-slate-500 uppercase mt-0.5">{{ $task->equipment->type }}</div>
                            </td>
                            <td>
                                <span class="font-medium text-slate-800">{{ str_replace('_', ' ', ucwords($task->task_type)) }}</span>
                            </td>
                            <td>
                                <span x-show="!editing" class="badge {{ $task->status === 'completed' ? 'badge-resolved' : ($task->status === 'in_progress' ? 'badge-in-progress' : 'badge-open') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                
                                <form x-show="editing" action="{{ route('cleaning-tasks.update', $task) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="text-xs rounded-md border-slate-200 py-1 pl-2 pr-6" onchange="this.form.submit()">
                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">{{ strtoupper(substr($task->performer->name, 0, 1)) }}</div>
                                    <span class="text-sm text-slate-600">{{ $task->performer->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">
                                    {{ $task->completed_at ? $task->completed_at->format('M d, Y') : ($task->scheduled_at ? $task->scheduled_at->format('M d, Y') : $task->created_at->format('M d, Y')) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button @click="editing = !editing" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                                    <span x-text="editing ? 'Cancel' : 'Update'"></span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No cleaning tasks found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $cleaningTasks->links() }}
        </div>
    </div>
</x-app-layout>
