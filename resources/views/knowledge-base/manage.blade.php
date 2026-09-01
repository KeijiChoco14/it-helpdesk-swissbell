<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Manage Knowledge Base</h1>
    </x-slot>

    <div class="glass-card mb-6">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Articles</h2>
                <p class="text-sm text-slate-500 mt-0.5">Create, edit, and publish knowledge base articles</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('knowledge-base.index') }}" class="btn btn-secondary">
                    View Public
                </a>
                <a href="{{ route('knowledge-base.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New Article
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Updated</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <a href="{{ route('knowledge-base.show', $article) }}" class="font-medium text-slate-800 hover:text-red-600">
                                    {{ Str::limit($article->title, 40) }}
                                </a>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $article->category ?? 'General' }}</span>
                            </td>
                            <td>
                                @if($article->is_published)
                                    <span class="badge badge-resolved">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ number_format($article->views) }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $article->updated_at->format('M d, Y') }}</span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('knowledge-base.edit', $article) }}" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                    </a>
                                    <form action="{{ route('knowledge-base.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No articles found</p>
                                <p class="text-xs text-slate-400 mt-1">Create one to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
    </div>
</x-app-layout>
