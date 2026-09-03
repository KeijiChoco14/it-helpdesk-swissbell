<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Tickets - Kanban Board</h1>
    </x-slot>

    <div class="mb-4 flex items-center justify-between">
        <p class="text-slate-600">Drag and drop tickets to change their status.</p>
        <a href="{{ route('service-requests.index') }}" class="btn btn-secondary bg-white text-slate-700 hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
            List View
        </a>
    </div>

    <!-- Kanban Board Container -->
    <div class="flex items-start gap-4 overflow-x-auto pb-4" style="min-height: 65vh;">
        
        @php
            $columns = [
                'OPEN' => ['title' => 'Open', 'color' => 'bg-red-50', 'border' => 'border-red-200', 'header' => 'text-red-700'],
                'IN_PROGRESS' => ['title' => 'In Progress', 'color' => 'bg-amber-50', 'border' => 'border-amber-200', 'header' => 'text-amber-700'],
                'RESOLVED' => ['title' => 'Resolved', 'color' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'header' => 'text-emerald-700'],
                'CLOSED' => ['title' => 'Closed', 'color' => 'bg-slate-50', 'border' => 'border-slate-200', 'header' => 'text-slate-700'],
            ];
        @endphp

        @foreach($columns as $status => $col)
            <div class="flex-shrink-0 w-80 bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col h-full max-h-[75vh]">
                <!-- Column Header -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between {{ $col['color'] }} rounded-t-xl">
                    <h3 class="font-bold {{ $col['header'] }}">{{ $col['title'] }}</h3>
                    <span class="bg-white px-2 py-0.5 rounded-md text-xs font-semibold text-slate-500 shadow-sm border {{ $col['border'] }}">
                        {{ count($kanban[$status]) }}
                    </span>
                </div>
                
                <!-- Droppable Area -->
                <div class="p-3 flex-1 overflow-y-auto bg-slate-50/50 kanban-column" data-status="{{ $status }}" style="min-height: 150px;">
                    @foreach($kanban[$status] as $serviceRequest)
                        <!-- Ticket Card -->
                        <div class="bg-white border border-slate-200 p-3 rounded-lg shadow-sm mb-3 cursor-grab hover:border-slate-300 transition-colors" data-id="{{ $serviceRequest->id }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-mono text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded">{{ $serviceRequest->ticket_number }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $serviceRequest->priority === 'High' || $serviceRequest->priority === 'Critical' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $serviceRequest->priority }}
                                </span>
                            </div>
                            <h4 class="font-medium text-slate-800 text-sm mb-2 leading-snug">{{ $serviceRequest->title }}</h4>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    @if($serviceRequest->assignedUser)
                                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[9px] font-bold text-white" title="{{ $serviceRequest->assignedUser->name }}">
                                            {{ strtoupper(substr($serviceRequest->assignedUser->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                        </div>
                                    @endif
                                    <span class="text-xs text-slate-500 truncate max-w-[100px]">{{ $serviceRequest->assignedUser->name ?? 'Unassigned' }}</span>
                                </div>
                                <a href="{{ route('service-requests.show', $serviceRequest) }}" class="text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

    <!-- Load SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.kanban-column');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban', // set both lists to same group
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function (evt) {
                        const itemEl = evt.item;  // dragged HTMLElement
                        const toColumn = evt.to;    // target list
                        
                        const ticketId = itemEl.getAttribute('data-id');
                        const newStatus = toColumn.getAttribute('data-status');
                        const oldStatus = evt.from.getAttribute('data-status');
                        
                        // If status didn't change, do nothing
                        if (newStatus === oldStatus) return;

                        // Send AJAX request to update status
                        fetch(`/service-requests/${ticketId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update counts (optional visual enhancement)
                                // In a real app we might update the counter spans
                            } else {
                                alert('Failed to update status. Make sure you have permission.');
                                // Revert drag
                                evt.from.insertBefore(itemEl, evt.from.children[evt.oldIndex]);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating status.');
                            // Revert drag
                            evt.from.insertBefore(itemEl, evt.from.children[evt.oldIndex]);
                        });
                    },
                });
            });
        });
    </script>
</x-app-layout>


