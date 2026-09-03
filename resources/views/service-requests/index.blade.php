<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Service Requests</h1>
    </x-slot>

    <div class="glass-card">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">All Requests</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage and track support requests</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('service-requests.kanban') }}" class="btn btn-secondary bg-white text-slate-700 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    Kanban View
                </a>
                @can('viewAny', App\Models\ServiceRequest::class)
                    <a href="{{ route('service-requests.export') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Export CSV
                    </a>
                @endcan
                @can('create', App\Models\ServiceRequest::class)
                    <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        New Request
                    </a>
                @endcan
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceRequests as $serviceRequest)
                        <tr>
                            <td>
                                <span class="font-mono text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-md">{{ $serviceRequest->ticket_number }}</span>
                            </td>
                            <td>
                                <span class="font-medium text-slate-800">{{ Str::limit($serviceRequest->title, 35) }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $serviceRequest->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $serviceRequest->status === 'OPEN' ? 'badge-open' : ($serviceRequest->status === 'IN_PROGRESS' ? 'badge-in-progress' : ($serviceRequest->status === 'RESOLVED' ? 'badge-resolved' : 'badge-closed')) }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $serviceRequest->status === 'OPEN' ? 'bg-red-500' : ($serviceRequest->status === 'IN_PROGRESS' ? 'bg-amber-500' : ($serviceRequest->status === 'RESOLVED' ? 'bg-emerald-500' : 'bg-slate-400')) }}"></span>
                                    {{ $serviceRequest->status }}
                                </span>
                                @if($serviceRequest->is_escalated)
                                    <span class="badge bg-red-100 text-red-700 border-red-200 mt-1 block w-fit text-[10px]">
                                        OVERDUE
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $serviceRequest->priority === 'Low' ? 'badge-priority-low' : ($serviceRequest->priority === 'Medium' ? 'badge-priority-medium' : ($serviceRequest->priority === 'High' ? 'badge-priority-high' : 'badge-priority-critical')) }}">
                                    {{ $serviceRequest->priority }}
                                </span>
                            </td>
                            <td>
                                @if($serviceRequest->assignedUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($serviceRequest->assignedUser->name, 0, 1)) }}</div>
                                        <span class="text-sm text-slate-600">{{ $serviceRequest->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('service-requests.show', $serviceRequest) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
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
            {{ $serviceRequests->links() }}
        </div>
    </div>
</x-app-layout>




