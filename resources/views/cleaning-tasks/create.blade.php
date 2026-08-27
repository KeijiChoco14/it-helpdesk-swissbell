<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">New Cleaning Task</h1>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card p-6 md:p-8">
            <form action="{{ route('cleaning-tasks.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Equipment -->
                <div>
                    <label for="equipment_id" class="form-label">Device / Equipment</label>
                    <select name="equipment_id" id="equipment_id" class="form-input @error('equipment_id') border-red-500 @enderror" required autofocus>
                        <option value="">-- Select Device --</option>
                        @foreach($equipments as $equipment)
                            <option value="{{ $equipment->id }}" {{ (old('equipment_id') == $equipment->id || request('equipment_id') == $equipment->id) ? 'selected' : '' }}>
                                {{ $equipment->name }} ({{ strtoupper($equipment->type) }}) 
                                @if($equipment->user) - Assigned to {{ $equipment->user->name }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_id')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Task Type -->
                <div>
                    <label for="task_type" class="form-label">Task Type</label>
                    <select name="task_type" id="task_type" class="form-input @error('task_type') border-red-500 @enderror" required>
                        <option value="cleaning_pc" {{ old('task_type') == 'cleaning_pc' ? 'selected' : '' }}>PC Cleaning</option>
                        <option value="thermal_paste" {{ old('task_type') == 'thermal_paste' ? 'selected' : '' }}>Thermal Paste Replacement</option>
                        <option value="dust_removal" {{ old('task_type') == 'dust_removal' ? 'selected' : '' }}>Dust Removal</option>
                        <option value="deep_clean" {{ old('task_type') == 'deep_clean' ? 'selected' : '' }}>Deep Clean</option>
                        <option value="other" {{ old('task_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('task_type')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description / Details</label>
                    <textarea name="description" id="description" rows="3" class="form-input @error('description') border-red-500 @enderror" placeholder="Specific notes about what needs to be done...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Schedule -->
                <div>
                    <label for="scheduled_at" class="form-label">Scheduled For (Optional)</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-input @error('scheduled_at') border-red-500 @enderror" value="{{ old('scheduled_at') }}">
                    @error('scheduled_at')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('cleaning-tasks.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
