<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Knowledge Base</h1>
    </x-slot>

    <div class="glass-card mb-6">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Search Solutions</h2>
                <p class="text-sm text-slate-500 mt-0.5">Find answers to common IT issues before opening a ticket</p>
            </div>
            @if(auth()->user()->isItStaff())
                <a href="{{ route('knowledge-base.manage') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>
                    Manage Articles
                </a>
            @endif
        </div>

        <div class="px-6 py-6">
            <form action="{{ route('knowledge-base.index') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" class="input !pl-11 py-3 text-lg" placeholder="Search for how to reset password, printer offline, etc..." />
                    <button type="submit" class="absolute right-2 px-4 py-1.5 bg-red-600 text-white rounded-md text-sm font-semibold hover:bg-red-700 transition-colors">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <a href="{{ route('knowledge-base.show', $article) }}" class="glass-card hover:-translate-y-1 transition-all duration-300 block overflow-hidden group">
                <div class="px-6 py-5 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                            {{ $article->category ?? 'General' }}
                        </span>
                        <span class="text-xs text-slate-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            {{ number_format($article->views) }}
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-red-600 transition-colors mb-2 line-clamp-2">
                        {{ $article->title }}
                    </h3>
                    
                    <p class="text-sm text-slate-500 line-clamp-3 mb-4 flex-grow">
                        {{ strip_tags($article->content) }}
                    </p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center text-red-600 text-sm font-semibold group-hover:gap-1 transition-all">
                        Read Article <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                <h3 class="text-xl font-bold text-slate-700">No articles found</h3>
                <p class="text-slate-500 mt-2">Try a different search term or browse all articles.</p>
                @if($search)
                    <a href="{{ route('knowledge-base.index') }}" class="btn btn-secondary mt-6 inline-flex">Clear Search</a>
                @endif
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</x-app-layout>
