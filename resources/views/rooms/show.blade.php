<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('rooms.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Room {{ $room->room_number }}</h1>
            <x-room-status-badge :status="$room->status" />
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Room Info -->
        <div class="lg:col-span-1">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Room Details</h2>
                    @can('update', $room)
                        <a href="{{ route('rooms.edit', $room) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Edit</a>
                    @endcan
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Room Type</p>
                        <p class="text-slate-800 font-medium">{{ $room->roomType->name }} <span class="text-xs text-slate-400">({{ $room->roomType->code }})</span></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Floor</p>
                        <p class="text-slate-800 font-medium">Level {{ $room->floor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Capacity</p>
                        <p class="text-slate-800 font-medium">{{ $room->roomType->capacity ?? 'N/A' }} Persons</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Description</p>
                        <p class="text-slate-700 text-sm mt-1">{{ $room->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

                <!-- Service Request History -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Housekeeping History</h2>
                    @can('create', App\Models\HousekeepingTask::class)
                        <a href="{{ route('housekeeping.tasks.create', ['room_id' => $room->id]) }}" class="btn btn-secondary py-1.5 px-3 text-sm">New Task</a>
                    @endcan
                </div>

                @if($room->housekeepingTasks->count() > 0)
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Task #</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->housekeepingTasks()->latest()->take(5)->get() as $hk)
                                    <tr class="cursor-pointer hover:bg-slate-50" onclick="window.location='{{ route('housekeeping.tasks.show', $hk) }}'">
                                        <td><span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $hk->task_number }}</span></td>
                                        <td><x-task-type-badge :type="$hk->task_type" /></td>
                                        <td><x-housekeeping-status-badge :status="$hk->status" /></td>
                                        <td><span class="text-sm text-slate-600">{{ $hk->assignee->name ?? 'Unassigned' }}</span></td>
                                        <td><span class="text-sm text-slate-500">{{ $hk->created_at->format('M d, Y') }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 bg-slate-50/50 rounded-lg border border-dashed border-slate-200">
                        <p class="text-slate-500 font-medium text-sm">No recent housekeeping tasks.</p>
                    </div>
                @endif
            </div>

            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Service Request History</h2>
                    @can('create', App\Models\ServiceRequest::class)
                        <a href="{{ route('service-requests.create', ['room_id' => $room->id]) }}" class="btn btn-primary py-1.5 px-3 text-sm">New Request</a>
                    @endcan
                </div>

                @if($serviceRequests->count() > 0)
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Req #</th>
                                    <th>Title</th>
                                    <th>Dept</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceRequests as $req)
                                    <tr class="cursor-pointer hover:bg-slate-50" onclick="window.location='{{ route('service-requests.show', $req) }}'">
                                        <td><span class="font-mono text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-md">{{ $req->ticket_number }}</span></td>
                                        <td>
                                            <p class="font-medium text-slate-800 truncate max-w-[150px]" title="{{ $req->title }}">{{ $req->title }}</p>
                                        </td>
                                        <td><span class="text-sm text-slate-600">{{ $req->department->name ?? 'N/A' }}</span></td>
                                        <td>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                                {{ str_replace('_', ' ', $req->status) }}
                                            </span>
                                        </td>
                                        <td><span class="text-sm text-slate-500">{{ $req->created_at->format('M d, Y') }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $serviceRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50/50 rounded-lg border border-dashed border-slate-200">
                        <p class="text-slate-500 font-medium">No service requests for this room.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

