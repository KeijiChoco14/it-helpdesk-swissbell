<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Create New Ticket</h1>
    </x-slot>

    <div class="max-w-3xl">
        <div class="glass-card">
            <div class="px-8 py-6 border-b border-slate-200/60 bg-gradient-to-r from-slate-50 to-white">
                <h2 class="text-lg font-bold text-slate-800">Report an IT Issue</h2>
                <p class="text-sm text-slate-500 mt-1">Fill in the details below and our IT team will get back to you ASAP.</p>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="form-label">Subject / Title</label>
                    <input type="text" name="title" id="title" class="form-input" placeholder="e.g. Printer not working in Front Office" required value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Category + Priority Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-input" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->type }})</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-input" required>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>🟠 High</option>
                            <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>🔴 Critical</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="5" class="form-input" placeholder="Describe the issue in detail — what happened, when it started, and what you've already tried..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location + Device Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="form-label">Location <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="location" id="location" class="form-input" placeholder="e.g. Lobby, Room 205" value="{{ old('location') }}">
                    </div>
                    <div>
                        <label for="device" class="form-label">Device / Asset <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="device" id="device" class="form-input" placeholder="e.g. HP LaserJet Pro" value="{{ old('device') }}">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('tickets.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
