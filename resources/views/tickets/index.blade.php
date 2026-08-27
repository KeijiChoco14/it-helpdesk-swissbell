<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Tickets</h1>
    </x-slot>

    <div class="glass-card">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">All Tickets</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage and track support requests</p>
            </div>
            @can('create', App\Models\Ticket::class)
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New Ticket
                </a>
            @endcan
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <span class="font-mono text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-md">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td>
                                <span class="font-medium text-slate-800">{{ Str::limit($ticket->title, 35) }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $ticket->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $ticket->status === 'OPEN' ? 'badge-open' : ($ticket->status === 'IN_PROGRESS' ? 'badge-in-progress' : ($ticket->status === 'RESOLVED' ? 'badge-resolved' : 'badge-closed')) }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ticket->status === 'OPEN' ? 'bg-red-500' : ($ticket->status === 'IN_PROGRESS' ? 'bg-amber-500' : ($ticket->status === 'RESOLVED' ? 'bg-emerald-500' : 'bg-slate-400')) }}"></span>
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ticket->priority === 'Low' ? 'badge-priority-low' : ($ticket->priority === 'Medium' ? 'badge-priority-medium' : ($ticket->priority === 'High' ? 'badge-priority-high' : 'badge-priority-critical')) }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                @if($ticket->assignedUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($ticket->assignedUser->name, 0, 1)) }}</div>
                                        <span class="text-sm text-slate-600">{{ $ticket->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                                <p class="text-sm font-medium text-slate-400">No tickets found</p>
                                <p class="text-xs text-slate-400 mt-1">Create one to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $tickets->links() }}
        </div>
    </div>
</x-app-layout>
