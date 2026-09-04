<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Housekeeping Dashboard</h1>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-6 border-l-4 border-slate-500">
            <h3 class="text-sm font-medium text-slate-500 mb-1">Total Tasks</h3>
            <p class="text-3xl font-bold text-slate-800">{{ $totalTasks }}</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-blue-500">
            <h3 class="text-sm font-medium text-slate-500 mb-1">Assigned</h3>
            <p class="text-3xl font-bold text-blue-700">{{ $assigned }}</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-amber-500">
            <h3 class="text-sm font-medium text-slate-500 mb-1">In Progress</h3>
            <p class="text-3xl font-bold text-amber-700">{{ $inProgress }}</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-indigo-500">
            <h3 class="text-sm font-medium text-slate-500 mb-1">Completed (Wait Inspect)</h3>
            <p class="text-3xl font-bold text-indigo-700">{{ $completed }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- My Assigned Tasks -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800">My Assigned Tasks</h2>
                <a href="{{ route('housekeeping.tasks.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
            </div>
            
            <div class="glass-card overflow-hidden">
                @if($myTasks->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($myTasks as $task)
                            <a href="{{ route('housekeeping.tasks.show', $task) }}" class="block p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-semibold text-slate-500">{{ $task->task_number }}</span>
                                        <x-housekeeping-status-badge :status="$task->status" />
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $task->scheduled_at ? $task->scheduled_at->format('M d, H:i') : 'No schedule' }}</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="font-bold text-slate-800">Room {{ $task->room->room_number }}</p>
                                        <p class="text-sm text-slate-500">{{ $task->room->roomType->name }} &bull; Floor {{ $task->room->floor }}</p>
                                    </div>
                                    <x-task-type-badge :type="$task->task_type" />
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-slate-500">
                        You have no pending tasks.
                    </div>
                @endif
            </div>
        </div>

        <!-- Rooms Requiring Attention -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800">Rooms Requiring Attention</h2>
                @if(in_array(auth()->user()->role, ['it_admin', 'it_support']))
                    <a href="{{ route('rooms.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View Rooms</a>
                @endif
            </div>

            <div class="glass-card overflow-hidden">
                @if($roomsRequiringAttention->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($roomsRequiringAttention as $room)
                            <a href="{{ route('rooms.show', $room) }}" class="block p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-bold text-slate-800">Room {{ $room->room_number }}</h3>
                                    <x-room-status-badge :status="$room->status" />
                                </div>
                                <p class="text-sm text-slate-500 mb-2">{{ $room->roomType->name }}</p>
                                @if($room->housekeepingTasks->isNotEmpty())
                                    @php $latest = $room->housekeepingTasks->first(); @endphp
                                    <div class="bg-slate-50 rounded p-2 text-xs text-slate-600 flex items-center justify-between">
                                        <span>Latest: {{ $latest->task_number }} ({{ $latest->status->label() }})</span>
                                        <span class="text-slate-400">{{ $latest->created_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400 italic">No recent housekeeping tasks.</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-slate-500">
                        All rooms are clear.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
