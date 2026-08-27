<!-- Sidebar Navigation -->
<aside class="sidebar" x-data="{ open: true }">
    <!-- Logo -->
    <div class="px-6 py-5 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796l-3.448 4.138m0 0a3.765 3.765 0 00-2.528 0m2.528 0l-4.138 3.448m0 0a9.027 9.027 0 01-1.306-1.652m1.306 1.652l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.736 3.736 0 01-.88 1.388 3.737 3.737 0 01-1.388.88m2.268-2.268l-4.138 3.448M7.288 4.33l3.448 4.138m0 0a3.765 3.765 0 012.528 0m-2.528 0L7.288 4.33m0 0A9.027 9.027 0 005.982 5.636M19.67 16.712l-4.138-3.448"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">IT Helpdesk</h1>
                <p class="text-[11px] text-slate-400 font-medium">Hotel Support System</p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
        <p class="px-6 pt-2 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Main Menu</p>

        @php $role = auth()->user()->role; @endphp

        @if($role === 'employee')
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Dashboard
            </a>
        @elseif($role === 'it_support')
            <a href="{{ route('dashboard.it') }}" class="sidebar-link {{ request()->routeIs('dashboard.it') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Dashboard
            </a>
        @elseif($role === 'it_admin')
            <a href="{{ route('dashboard.admin') }}" class="sidebar-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Dashboard
            </a>
        @endif

        <a href="{{ route('tickets.index') }}" class="sidebar-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
            Tickets
        </a>

        @if($role === 'employee')
            <a href="{{ route('tickets.create') }}" class="sidebar-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                New Ticket
            </a>
        @endif

        <p class="px-6 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Management</p>

        @if($role === 'it_admin')
            <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                Departments
            </a>
            <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                Users & Staff
            </a>
        @endif

        <a href="{{ route('equipment.index') }}" class="sidebar-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
            Equipment
        </a>

        @if(in_array($role, ['it_admin', 'it_support']))
            <a href="{{ route('cleaning-tasks.index') }}" class="sidebar-link {{ request()->routeIs('cleaning-tasks.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1 3.07A1.5 1.5 0 014.5 17V7a1.5 1.5 0 011.82-1.24l5.1 3.07a1.5 1.5 0 010 2.34zM11.42 15.17l5.1 3.07A1.5 1.5 0 0018.34 17V7a1.5 1.5 0 00-1.82-1.24l-5.1 3.07a1.5 1.5 0 000 2.34z" /></svg>
                Cleaning Tasks
            </a>
        @endif
    </nav>

    <!-- User Info -->
    <div class="border-t border-slate-800 px-4 py-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-sm font-bold text-white shadow-md">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-slate-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</aside>
