<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('knowledge-base.manage') }}" class="p-2 rounded-full hover:bg-slate-200 transition-colors">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Create Article</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card">
            <div class="px-6 py-5 border-b border-slate-200/60">
                <h2 class="text-lg font-bold text-slate-800">Article Details</h2>
                <p class="text-sm text-slate-500 mt-0.5">Write a new guide for the knowledge base</p>
            </div>
            
            <form action="{{ route('knowledge-base.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div>
                    <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required autofocus>
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="input @error('category') border-red-500 @enderror">
                        <option value="">General</option>
                        <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                        <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Network" {{ old('category') == 'Network' ? 'selected' : '' }}>Network</option>
                        <option value="Accounts" {{ old('category') == 'Accounts' ? 'selected' : '' }}>Accounts & Access</option>
                        <option value="Printers" {{ old('category') == 'Printers' ? 'selected' : '' }}>Printers</option>
                    </select>
                    @error('category')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content" class="form-label">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="10" class="input @error('content') border-red-500 @enderror" required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1" class="w-4 h-4 text-red-600 rounded border-slate-300 focus:ring-red-600" {{ old('is_published', true) ? 'checked' : '' }}>
                    <label for="is_published" class="text-sm font-medium text-slate-700">Publish immediately</label>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('knowledge-base.manage') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
