<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">IT Admin Dashboard</h1>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="glass-card bg-gradient-to-r from-red-600 to-purple-600 p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }} 👋</h2>
                <p class="text-red-200 mt-1">Here's what's happening with your helpdesk today.</p>
            </div>
            <div class="hidden md:block">
                <p class="text-red-200 text-sm">{{ now()->format('l, M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat-card bg-white border border-slate-200/60 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Unassigned</p>
                    <p class="text-4xl font-black text-red-600 mt-2">{{ $stats['total_unassigned'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Needs attention</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white border border-slate-200/60 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Open Tickets</p>
                    <p class="text-4xl font-black text-blue-600 mt-2">{{ $stats['total_open'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Awaiting response</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white border border-slate-200/60 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">In Progress</p>
                    <p class="text-4xl font-black text-emerald-600 mt-2">{{ $stats['total_in_progress'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Being worked on</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1 3.07A1.5 1.5 0 014.5 17V7a1.5 1.5 0 011.82-1.24l5.1 3.07a1.5 1.5 0 010 2.34z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- By Category -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                Tickets by Category
            </h3>
            <div class="space-y-3">
                @foreach($stats['by_category'] as $category)
                    @php $max = $stats['by_category']->max('tickets_count') ?: 1; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-slate-700">{{ $category->name }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ $category->tickets_count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-red-500 to-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($category->tickets_count / $max) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- By Status -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Tickets by Status
            </h3>
            <div class="space-y-4">
                @foreach($stats['by_status'] as $statusStat)
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $statusStat->status === 'OPEN' ? 'bg-red-50' : ($statusStat->status === 'IN_PROGRESS' ? 'bg-amber-50' : ($statusStat->status === 'RESOLVED' ? 'bg-emerald-50' : 'bg-slate-50')) }}">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $statusStat->status === 'OPEN' ? 'bg-red-500' : ($statusStat->status === 'IN_PROGRESS' ? 'bg-amber-500' : ($statusStat->status === 'RESOLVED' ? 'bg-emerald-500' : 'bg-slate-400')) }}"></div>
                            <span class="text-sm font-semibold text-slate-700">{{ $statusStat->status }}</span>
                        </div>
                        <span class="text-2xl font-black {{ $statusStat->status === 'OPEN' ? 'text-red-600' : ($statusStat->status === 'IN_PROGRESS' ? 'text-amber-600' : ($statusStat->status === 'RESOLVED' ? 'text-emerald-600' : 'text-slate-600')) }}">{{ $statusStat->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('tickets.index') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                Manage Tickets
            </a>
        </div>
    </div>
</x-app-layout>
