<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IT Helpdesk') }} — Hotel IT Support</title>
        <meta name="description" content="Internal IT Helpdesk Ticketing System for hotel operations">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @auth
                @include('layouts.sidebar')
                <!-- Mobile overlay -->
                <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>
            @endauth

            <!-- Main Content Area -->
            <div class="flex-1 w-full min-w-0 @auth lg:ml-64 @endauth transition-all duration-300">
                <!-- Top Bar -->
                @auth
                    @include('layouts.topbar')
                @endauth

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mx-6 mt-4">
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3" x-data="{ show: true }" x-show="show" x-transition>
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

