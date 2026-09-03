<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('housekeeping.tasks.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Create Housekeeping Task</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card p-8">
            <form action="{{ route('housekeeping.tasks.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="room_id" class="form-label">Room <span class="text-red-500">*</span></label>
                        <select name="room_id" id="room_id" class="form-select" required>
                            <option value="">Select a Room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    Room {{ $room->room_number }} (Floor {{ $room->floor }})
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="task_type" class="form-label">Task Type <span class="text-red-500">*</span></label>
                        <select name="task_type" id="task_type" class="form-select" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->value }}" {{ old('task_type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('task_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="priority" class="form-label">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="form-label">Assign To (Optional)</label>
                        <select name="assigned_to" id="assigned_to" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($staff as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="scheduled_at" class="form-label">Scheduled Time (Optional)</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-input" value="{{ old('scheduled_at') }}">
                        @error('scheduled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-input" placeholder="Any special instructions?">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200/60 flex justify-end gap-3">
                    <a href="{{ route('housekeeping.tasks.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
