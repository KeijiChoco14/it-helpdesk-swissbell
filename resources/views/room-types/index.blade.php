<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Room Types</h1>
    </x-slot>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Manage Room Types</h2>
                <p class="text-sm text-slate-500 mt-0.5">Define room categories for the hotel</p>
            </div>
            <div class="flex items-center gap-3">
                @can('create', App\Models\RoomType::class)
                    <a href="{{ route('room-types.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        New Room Type
                    </a>
                @endcan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Capacity</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $type)
                        <tr>
                            <td><span class="font-medium text-slate-800">{{ $type->name }}</span></td>
                            <td><span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $type->code }}</span></td>
                            <td><span class="text-slate-600">{{ $type->capacity ?? '-' }}</span></td>
                            <td class="text-right">
                                @can('update', $type)
                                    <a href="{{ route('room-types.edit', $type) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                        Edit
                                    </a>
                                @endcan
                                @can('delete', $type)
                                    <form action="{{ route('room-types.destroy', $type) }}" method="POST" class="inline-block ml-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Are you sure you want to delete this room type?')">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No room types found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $roomTypes->links() }}
        </div>
    </div>
</x-app-layout>
