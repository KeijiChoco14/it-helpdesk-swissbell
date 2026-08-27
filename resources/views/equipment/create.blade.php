<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Add Equipment</h1>
    </x-slot>

    <div class="max-w-3xl">
        <div class="glass-card p-6 md:p-8">
            <form action="{{ route('equipment.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Device Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="form-label">Device Name / Identifier</label>
                        <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. PC Desktop Front Desk">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-input @error('type') border-red-500 @enderror" required>
                            <option value="pc" {{ old('type') == 'pc' ? 'selected' : '' }}>PC Desktop</option>
                            <option value="laptop" {{ old('type') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="printer" {{ old('type') == 'printer' ? 'selected' : '' }}>Printer</option>
                            <option value="monitor" {{ old('type') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                            <option value="network" {{ old('type') == 'network' ? 'selected' : '' }}>Network Device</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned User -->
                    <div>
                        <label for="user_id" class="form-label">Assigned To (Optional)</label>
                        <select name="user_id" id="user_id" class="form-input @error('user_id') border-red-500 @enderror">
                            <option value="">-- Unassigned --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ str_replace('_', ' ', $user->role) }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" name="brand" id="brand" class="form-input @error('brand') border-red-500 @enderror" value="{{ old('brand') }}" placeholder="e.g. Dell, HP, Lenovo">
                        @error('brand')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="form-label">Model</label>
                        <input type="text" name="model" id="model" class="form-input @error('model') border-red-500 @enderror" value="{{ old('model') }}" placeholder="e.g. OptiPlex 7090">
                        @error('model')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-input @error('serial_number') border-red-500 @enderror" value="{{ old('serial_number') }}">
                        @error('serial_number')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-input @error('location') border-red-500 @enderror" value="{{ old('location') }}" placeholder="e.g. Room 101, Front Desk">
                        @error('location')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-input @error('purchase_date') border-red-500 @enderror" value="{{ old('purchase_date') }}">
                        @error('purchase_date')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-input @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('equipment.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Save Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
