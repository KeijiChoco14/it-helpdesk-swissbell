<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Rooms Management</h1>
    </x-slot>

    <!-- Room Summary Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
        <div class="glass-card p-4 text-center">
            <p class="text-xs text-slate-500 font-semibold uppercase">Total</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-emerald-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">Available</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['available'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-blue-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">Occupied</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['occupied'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-amber-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">Dirty</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['dirty'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-indigo-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">Cleaning</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['cleaning'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-rose-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">Maintenance</p>
            <p class="text-2xl font-bold text-rose-600">{{ $stats['maintenance'] }}</p>
        </div>
        <div class="glass-card p-4 text-center border-b-4 border-slate-500">
            <p class="text-xs text-slate-500 font-semibold uppercase">O.O.O</p>
            <p class="text-2xl font-bold text-slate-600">{{ $stats['out_of_order'] }}</p>
        </div>
    </div>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Room List</h2>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full">
                    <input type="text" name="search" placeholder="Search Room..." value="{{ request('search') }}" class="form-input text-sm h-9 w-full sm:w-40">
                    
                    <select name="floor" class="form-select text-sm h-9 w-full sm:w-32">
                        <option value="">All Floors</option>
                        @for($i=1; $i<=10; $i++)
                            <option value="{{ $i }}" {{ request('floor') == $i ? 'selected' : '' }}>Floor {{ $i }}</option>
                        @endfor
                    </select>

                    <select name="room_type_id" class="form-select text-sm h-9 w-full sm:w-36">
                        <option value="">All Types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select text-sm h-9 w-full sm:w-36">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn btn-secondary h-9 px-3">Filter</button>
                    @if(request()->anyFilled(['search', 'floor', 'room_type_id', 'status']))
                        <a href="{{ route('rooms.index') }}" class="text-sm text-red-500 hover:underline">Clear</a>
                    @endif
                </form>

                @can('create', App\Models\Room::class)
                    <div class="hidden sm:block w-px h-6 bg-slate-200 mx-1"></div>
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary h-9 whitespace-nowrap">
                        New Room
                    </a>
                @endcan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Floor</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Open Requests</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td><span class="font-bold text-slate-800">{{ $room->room_number }}</span></td>
                            <td><span class="text-slate-600">Level {{ $room->floor }}</span></td>
                            <td><span class="text-sm text-slate-600">{{ $room->roomType->name }}</span></td>
                            <td>
                                <x-room-status-badge :status="$room->status" />
                            </td>
                            <td>
                                @if($room->service_requests_count > 0)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold">
                                        {{ $room->service_requests_count }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                                    View
                                </a>
                                @can('update', $room)
                                    <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors ml-3">
                                        Edit
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No rooms found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $rooms->links() }}
        </div>
    </div>
</x-app-layout>
