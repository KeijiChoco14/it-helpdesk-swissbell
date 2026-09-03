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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
        <div class="stat-card bg-white border border-slate-200/60 shadow-sm rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Avg Rating</p>
                    <p class="text-4xl font-black text-amber-500 mt-2">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</p>
                    <p class="text-xs text-slate-400 mt-1">From user feedback</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- By Category -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                Tickets by Category
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <!-- By Status -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Tickets by Status
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Trend Chart -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.7-9m0 0l-5.94.34m5.94-.34l-.34 5.94"/></svg>
            Tickets Over Time (Last 30 Days)
        </h3>
        <div class="relative h-72 w-full">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('service-requests.index') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                Manage Tickets
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Pie Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryData = {
                labels: {!! json_encode($stats['by_category']->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($stats['by_category']->pluck('service_requests_count')) !!},
                    backgroundColor: [
                        '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#64748b'
                    ],
                    borderWidth: 0
                }]
            };
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: categoryData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });

            // Status Bar Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = {
                labels: {!! json_encode($stats['by_status']->pluck('status')) !!},
                datasets: [{
                    label: 'Tickets',
                    data: {!! json_encode($stats['by_status']->pluck('count')) !!},
                    backgroundColor: '#ef4444',
                    borderRadius: 6
                }]
            };
            new Chart(statusCtx, {
                type: 'bar',
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Trend Line Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendData = {
                labels: {!! json_encode($stats['serviceRequests_over_time']->pluck('date')) !!},
                datasets: [{
                    label: 'Tickets Created',
                    data: {!! json_encode($stats['serviceRequests_over_time']->pluck('count')) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            };
            new Chart(trendCtx, {
                type: 'line',
                data: trendData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>



