<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('knowledge-base.index') }}" class="p-2 rounded-full hover:bg-slate-200 transition-colors">
                <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Article Details</h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="glass-card">
            <div class="px-8 py-10">
                <div class="flex items-center justify-between mb-6">
                    <span class="px-3 py-1 bg-red-50 text-red-700 rounded-full text-sm font-semibold border border-red-100">
                        {{ $knowledgeBaseArticle->category ?? 'General' }}
                    </span>
                    
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            Updated {{ $knowledgeBaseArticle->updated_at->diffForHumans() }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            {{ number_format($knowledgeBaseArticle->views) }} Views
                        </span>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-slate-900 mb-8">{{ $knowledgeBaseArticle->title }}</h1>

                <div class="prose prose-slate max-w-none">
                    {!! nl2br(e($knowledgeBaseArticle->content)) !!}
                </div>
            </div>
            
            @if(auth()->user()->isItStaff())
            <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex justify-end gap-3">
                <a href="{{ route('knowledge-base.edit', $knowledgeBaseArticle) }}" class="btn btn-secondary">
                    Edit Article
                </a>
            </div>
            @endif
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-slate-600 mb-4">Did this article help solve your issue?</p>
            <div class="flex items-center justify-center gap-4">
                <button class="btn btn-secondary flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152.26 2.243.723 3.218.266.558.107 1.282-.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.28.602.197.363.652.531 1.05.385l1.32-.486" /></svg>
                    Yes, it helped
                </button>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    No, Create Ticket
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
