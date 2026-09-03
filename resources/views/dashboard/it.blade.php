<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">IT Support Dashboard</h1>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="glass-card bg-gradient-to-r from-emerald-600 to-teal-600 p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Welcome, {{ auth()->user()->name }} 🛠️</h2>
                <p class="text-emerald-200 mt-1">IT Support Technician · Here are your assigned tickets.</p>
            </div>
            <div class="hidden md:block">
                <p class="text-emerald-200 text-sm">{{ now()->format('l, M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="stat-card bg-white border border-slate-200/60 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">My Open Tickets</p>
                    <p class="text-4xl font-black text-blue-600 mt-2">{{ $stats['assigned_open'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Assigned and open</p>
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
                    <p class="text-4xl font-black text-emerald-600 mt-2">{{ $stats['assigned_in_progress'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-1">Currently working on</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1 3.07A1.5 1.5 0 014.5 17V7a1.5 1.5 0 011.82-1.24l5.1 3.07a1.5 1.5 0 010 2.34z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card p-6 text-center">
        <a href="{{ route('service-requests.index') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            View Ticket Queue
        </a>
    </div>
</x-app-layout>

