<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Departments</h1>
    </x-slot>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Hotel Departments</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage custom departments for your hotel</p>
            </div>
            @can('create', App\Models\Department::class)
                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add Department
                </a>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Total Users</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">{{ $department->name }}</span>
                            </td>
                            <td>
                                @if($department->is_active)
                                    <span class="badge badge-resolved">Active</span>
                                @else
                                    <span class="badge badge-closed">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $department->users_count }} Users</span>
                            </td>
                            <td class="text-right">
                                @can('update', $department)
                                    <a href="{{ route('departments.edit', $department) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors mr-3">
                                        Edit
                                    </a>
                                @endcan
                                
                                @can('delete', $department)
                                    @if($department->users_count == 0)
                                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic" title="Cannot delete because it has users assigned">In Use</span>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No departments found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $departments->links() }}
        </div>
    </div>
</x-app-layout>
