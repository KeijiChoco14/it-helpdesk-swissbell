<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'IT Helpdesk') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Inter'] antialiased text-slate-600 bg-slate-50">

    <div class="min-h-screen flex">
        
        <!-- Left Side: Branding / Background -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1556761175-5973dc0f32d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center opacity-40 mix-blend-overlay"></div>
            
            <!-- Red Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/90 via-red-800/90 to-red-950/90"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between w-full p-12 lg:p-20 text-white">
                <div>
                    <img src="{{ asset('images/logoswissbell.jpg') }}" alt="Swiss-Belinn Logo" class="h-16 rounded-xl shadow-2xl bg-white p-1">
                </div>

                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-4">
                        IT Helpdesk & <br> Support System
                    </h1>
                    <p class="text-red-100/80 text-lg max-w-md leading-relaxed font-medium">
                        Sistem manajemen laporan masalah IT dan perawatan perangkat khusus untuk operasional Swiss-Belinn Pekanbaru.
                    </p>
                </div>

                <div class="flex items-center gap-4 text-sm text-red-200/60 font-medium">
                    <span>&copy; {{ date('Y') }} Swiss-Belinn Pekanbaru</span>
                    <span>&bull;</span>
                    <span>IT Department</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 lg:p-24 bg-white">
            <div class="w-full max-w-md">
                
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <img src="{{ asset('images/logoswissbell.jpg') }}" alt="Logo" class="h-16 mx-auto rounded-xl shadow-lg bg-white p-1 mb-4">
                    <h2 class="text-2xl font-bold text-slate-800">IT Helpdesk</h2>
                    <p class="text-slate-500 text-sm">Masuk ke akun Anda</p>
                </div>

                <div class="hidden lg:block mb-10">
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Selamat Datang 👋</h2>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Silakan masukkan email dan password Anda untuk masuk.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all duration-200 sm:text-sm font-medium" 
                                placeholder="nama@swiss-belhotel.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all duration-200 sm:text-sm font-medium" 
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500/30 transition-colors cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-sm font-medium text-slate-600 cursor-pointer">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-red-500/30 text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 active:scale-[0.98]">
                            Masuk ke Sistem
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>
</html>
