<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swiss-Belhotel IT Helpdesk') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            overflow-x: hidden;
        }
        
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: moveBlob 20s infinite alternate;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(56,189,248,0.4) 0%, rgba(59,130,246,0) 70%);
        }

        .blob-2 {
            bottom: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168,85,247,0.4) 0%, rgba(139,92,246,0) 70%);
            animation-delay: -5s;
        }

        .blob-3 {
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(45,212,191,0.2) 0%, rgba(16,185,129,0) 70%);
            animation-delay: -10s;
        }

        @keyframes moveBlob {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .gradient-text {
            background: linear-gradient(to right, #38bdf8, #818cf8, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .animated-border {
            position: relative;
        }
        .animated-border::before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 9999px;
            background: linear-gradient(45deg, #38bdf8, #818cf8, #a855f7, #38bdf8);
            background-size: 200%;
            z-index: -1;
            animation: borderGlow 3s linear infinite;
        }

        @keyframes borderGlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative selection:bg-indigo-500/30">
    
    <!-- Background Elements -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Navigation -->
    <nav class="w-full relative z-10 px-6 py-6 md:px-12 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-white">Swiss-Belhotel <span class="text-slate-400 font-medium">IT Helpdesk</span></span>
        </div>

        @if (Route::has('login'))
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white hover:text-indigo-300 transition-colors">Go to Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Log in</a>

                    @if (Route::has('register'))
                        <div class="animated-border rounded-full hidden sm:block">
                            <a href="{{ route('register') }}" class="block px-6 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-full hover:bg-slate-800 transition-colors">
                                Get Started
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center relative z-10 px-6 py-12">
        <div class="max-w-5xl w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Hero Text -->
            <div class="space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Systems Operational
                </div>
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.1]">
                    Modernize Your <br />
                    <span class="gradient-text">IT Support</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                    The centralized hub for Swiss-Belhotel staff. Submit tickets, request equipment, and get your technical issues resolved faster than ever.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 justify-center lg:justify-start">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                            Enter Dashboard
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 w-full sm:w-auto justify-center">
                            Log In to Portal
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-full glass-panel text-white font-semibold hover:bg-slate-800/50 transition-colors w-full sm:w-auto justify-center text-center">
                            Register Account
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features Card -->
            <div class="relative lg:ml-10">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl blur opacity-20 animate-pulse"></div>
                <div class="glass-panel rounded-2xl p-8 relative">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        Platform Features
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Feature 1 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-200">Ticket Management</h4>
                                <p class="text-sm text-slate-400 mt-1">Submit issues easily and track resolution progress in real-time with email notifications.</p>
                            </div>
                        </div>
                        
                        <!-- Feature 2 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-200">Equipment Request</h4>
                                <p class="text-sm text-slate-400 mt-1">Request new hardware and peripherals needed for your department seamlessly.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-lg bg-teal-500/10 border border-teal-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-teal-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-200">Knowledge Base</h4>
                                <p class="text-sm text-slate-400 mt-1">Access quick guides, troubleshooting manuals, and FAQs for common technical issues.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full relative z-10 px-6 py-6 border-t border-white/5 text-center flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-slate-500">&copy; {{ date('Y') }} Swiss-Belhotel International. All rights reserved.</p>
        <div class="text-xs text-slate-500 flex items-center gap-1">
            Built for <span class="font-semibold text-slate-400">Swiss-Belhotel</span> internal use
        </div>
    </footer>
</body>
</html>
