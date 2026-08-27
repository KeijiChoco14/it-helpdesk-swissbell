<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Edit Department</h1>
    </x-slot>

    <div class="max-w-xl">
        <div class="glass-card p-6 md:p-8">
            <form action="{{ route('departments.update', $department) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Department Name</label>
                    <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name', $department->name) }}" required autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-red-600 shadow-sm focus:ring-red-500" {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-slate-700">Active (Visible in selection lists)</label>
                </div>
                @error('is_active')
                    <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                @enderror

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('departments.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
