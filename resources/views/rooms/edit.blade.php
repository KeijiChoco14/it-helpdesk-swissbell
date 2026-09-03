<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('rooms.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Edit Room {{ $room->room_number }}</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card p-8">
            <form action="{{ route('rooms.update', $room) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="room_number" class="form-label">Room Number <span class="text-red-500">*</span></label>
                        <input type="text" name="room_number" id="room_number" class="form-input" value="{{ old('room_number', $room->room_number) }}" required>
                        @error('room_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="floor" class="form-label">Floor <span class="text-red-500">*</span></label>
                        <input type="number" name="floor" id="floor" class="form-input" value="{{ old('floor', $room->floor) }}" required>
                        @error('floor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="room_type_id" class="form-label">Room Type <span class="text-red-500">*</span></label>
                        <select name="room_type_id" id="room_type_id" class="form-select" required>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ old('status', $room->status->value) == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $room->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200/60 flex justify-between items-center">
                    <div>
                        @can('delete', $room)
                            <button type="button" class="btn btn-secondary text-red-600 hover:text-red-700 border-transparent hover:bg-red-50" onclick="if(confirm('Are you sure you want to delete this room?')) document.getElementById('delete-form').submit();">
                                Delete Room
                            </button>
                        @endcan
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Room</button>
                    </div>
                </div>
            </form>
            
            @can('delete', $room)
                <form id="delete-form" action="{{ route('rooms.destroy', $room) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
