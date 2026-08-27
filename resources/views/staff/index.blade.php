<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Staff Management</h1>
    </x-slot>

    <div class="glass-card">
        <div class="px-6 py-5 border-b border-slate-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">IT Staff Members</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage admins and support team</p>
            </div>
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                Add Staff
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-600">{{ $member->email ?? 'No Email' }}</div>
                                @if($member->job_title)
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $member->job_title }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $member->role === 'it_admin' ? 'badge-priority-critical' : 'badge-priority-medium' }}">
                                    {{ str_replace('_', ' ', strtoupper($member->role)) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">{{ $member->department->name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-right">
                                @if(auth()->id() !== $member->id)
                                    <form action="{{ route('staff.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this staff member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">You</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-400">No staff found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $staff->links() }}
        </div>
    </div>
</x-app-layout>
