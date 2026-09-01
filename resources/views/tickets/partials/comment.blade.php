<div class="flex gap-3">
    <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $comment->user->role === 'it_admin' ? 'from-red-400 to-orange-500' : ($comment->user->role === 'it_support' ? 'from-emerald-400 to-teal-500' : 'from-red-400 to-purple-500') }} flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</div>
    <div class="flex-1 bg-slate-50 rounded-xl p-4 border border-slate-100">
        <div class="flex items-center justify-between mb-1.5">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-800">{{ $comment->user->name }}</span>
                <span class="text-[10px] font-medium text-slate-400 uppercase px-1.5 py-0.5 bg-slate-100 rounded">{{ str_replace('_', ' ', $comment->user->role) }}</span>
            </div>
            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $comment->comment }}</p>
    </div>
</div>
