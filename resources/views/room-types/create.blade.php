<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('room-types.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Create Room Type</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card p-8">
            <form action="{{ route('room-types.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="form-label">Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" class="form-input" value="{{ old('code') }}" required placeholder="e.g. DLX">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="capacity" class="form-label">Capacity (Persons)</label>
                        <input type="number" name="capacity" id="capacity" class="form-input" value="{{ old('capacity') }}" min="1">
                        @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200/60 flex justify-end gap-3">
                    <a href="{{ route('room-types.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Room Type</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
