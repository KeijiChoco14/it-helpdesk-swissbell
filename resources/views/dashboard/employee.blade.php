<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Employee Dashboard</h1>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="glass-card bg-gradient-to-r from-sky-600 to-blue-600 p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Hello, {{ auth()->user()->name }} 👋</h2>
                <p class="text-sky-200 mt-1">{{ auth()->user()->department->name ?? 'No Department' }} · Need IT help? Create a ticket below.</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="btn bg-white text-sky-700 hover:bg-sky-50 shadow-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                New Ticket
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Resolved</p>
                    <p class="text-4xl font-black text-emerald-600 mt-2">{{ $stats['total_resolved'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Successfully completed</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- View Tickets Link -->
    <div class="glass-card p-6 text-center">
        <a href="{{ route('tickets.index') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            View My Tickets
        </a>
    </div>
</x-app-layout>
