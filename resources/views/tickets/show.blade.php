<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tickets.index') }}" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $ticket->ticket_number }}</h1>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Header -->
            <div class="glass-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ $ticket->title }}</h2>
                        <div class="flex items-center gap-2 mt-2 text-sm text-slate-500">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($ticket->user->name, 0, 1)) }}</div>
                            <span>{{ $ticket->user->name }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $ticket->department->name ?? 'N/A' }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <span class="badge {{ $ticket->status === 'OPEN' ? 'badge-open' : ($ticket->status === 'IN_PROGRESS' ? 'badge-in-progress' : ($ticket->status === 'RESOLVED' ? 'badge-resolved' : 'badge-closed')) }}">
                        <span class="w-2 h-2 rounded-full {{ $ticket->status === 'OPEN' ? 'bg-red-500' : ($ticket->status === 'IN_PROGRESS' ? 'bg-amber-500' : ($ticket->status === 'RESOLVED' ? 'bg-emerald-500' : 'bg-slate-400')) }}"></span>
                        {{ $ticket->status }}
                    </span>
                </div>
                <div class="prose prose-slate max-w-none text-sm leading-relaxed bg-slate-50 rounded-xl p-5 border border-slate-100">
                    {{ $ticket->description }}
                </div>
            </div>

            <!-- Comments -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200/60">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        Comments ({{ $ticket->comments->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4 mb-6">
                        @forelse($ticket->comments as $comment)
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
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                                <p class="text-sm text-slate-400">No comments yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    @if($ticket->status !== 'CLOSED')
                        <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST">
                            @csrf
                            <div class="relative">
                                <textarea name="comment" rows="3" class="form-input pr-24" placeholder="Write a comment..." required></textarea>
                                <div class="absolute bottom-3 right-3">
                                    <button type="submit" class="btn btn-primary text-xs px-3 py-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Properties -->
            <div class="glass-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Details</h3>
                <dl class="space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Category</dt>
                        <dd class="font-medium text-slate-800">{{ $ticket->category->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Priority</dt>
                        <dd>
                            <span class="badge {{ $ticket->priority === 'Low' ? 'badge-priority-low' : ($ticket->priority === 'Medium' ? 'badge-priority-medium' : ($ticket->priority === 'High' ? 'badge-priority-high' : 'badge-priority-critical')) }}">{{ $ticket->priority }}</span>
                        </dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                        <dt class="text-slate-500">Location</dt>
                        <dd class="font-medium text-slate-800">{{ $ticket->location ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Device</dt>
                        <dd class="font-medium text-slate-800">{{ $ticket->device ?? '—' }}</dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                        <dt class="text-slate-500">Assigned</dt>
                        <dd>
                            @if($ticket->assignedUser)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($ticket->assignedUser->name, 0, 1)) }}</div>
                                    <span class="font-medium text-slate-800">{{ $ticket->assignedUser->name }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Unassigned</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Assign -->
            @can('assign', $ticket)
                <div class="glass-card p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Assign Ticket</h3>
                    <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="space-y-3">
                        @csrf
                        <select name="assigned_to" class="form-input" required>
                            <option value="">Select technician</option>
                            @foreach(App\Models\User::whereIn('role', ['it_support', 'it_admin'])->get() as $itUser)
                                <option value="{{ $itUser->id }}" {{ $ticket->assigned_to == $itUser->id ? 'selected' : '' }}>{{ $itUser->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark w-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Assign
                        </button>
                    </form>
                </div>
            @endcan

            <!-- Actions -->
            @if($ticket->status !== 'CLOSED')
                <div class="glass-card p-6 space-y-3">
                    <h3 class="text-base font-bold text-slate-800 mb-2">Actions</h3>

                    @can('resolve', $ticket)
                        @if($ticket->status !== 'RESOLVED')
                            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="RESOLVED">
                                <button type="submit" class="btn btn-success w-full">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mark Resolved
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('close', $ticket)
                        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="CLOSED">
                            <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Are you sure?');">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Close Ticket
                            </button>
                        </form>
                    @endcan
                </div>
            @endif

            @if($ticket->status === 'CLOSED')
                @can('reopen', $ticket)
                    <div class="glass-card p-6">
                        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="OPEN">
                            <button type="submit" class="btn btn-warning w-full">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                Reopen Ticket
                            </button>
                        </form>
                    </div>
                @endcan
            @endif
        </div>
    </div>
</x-app-layout>
