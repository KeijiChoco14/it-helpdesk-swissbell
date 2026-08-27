<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Equipment List</h1>
    </x-slot>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Assigned Equipment</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage and track IT assets</p>
            </div>
            @can('create', App\Models\Equipment::class)
                <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Equipment
                </a>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Type</th>
                        <th>Assigned To</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipmentList as $equipment)
                        <tr>
                            <td>
                                <div class="font-medium text-slate-800">{{ $equipment->name }}</div>
                                <div class="text-xs text-slate-500">{{ $equipment->brand }} {{ $equipment->model }}</div>
                            </td>
                            <td>
                                <span class="badge badge-closed uppercase">{{ $equipment->type }}</span>
                            </td>
                            <td>
                                @if($equipment->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">{{ strtoupper(substr($equipment->user->name, 0, 1)) }}</div>
                                        <span class="text-sm text-slate-600">{{ $equipment->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $equipment->location ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $equipment->status === 'active' ? 'badge-resolved' : ($equipment->status === 'maintenance' ? 'badge-in-progress' : 'badge-closed') }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $equipment->status === 'active' ? 'bg-emerald-500' : ($equipment->status === 'maintenance' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                                    {{ ucfirst($equipment->status) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('equipment.show', $equipment) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No equipment found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $equipmentList->links() }}
        </div>
    </div>
</x-app-layout>
