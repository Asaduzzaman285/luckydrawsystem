<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LuckyDraw Pro') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-grid {
            background: radial-gradient(circle at top right, #1e3a8a 0%, #111827 100%);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        {{-- Main Container Card --}}
        <div class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px] border border-slate-100">
            
            {{-- Left/Top Panel: Branding & Logo --}}
            <div class="md:w-5/12 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 relative flex flex-col justify-center items-center p-12 text-center text-white overflow-hidden">
                {{-- Decorative background circles --}}
                <div class="absolute -top-12 -left-12 w-48 h-48 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-12 -right-12 w-48 h-48 rounded-full bg-blue-400/20 blur-3xl"></div>
                
                <div class="relative z-10 space-y-8">
                    <a href="/" class="inline-flex flex-col items-center group">
                        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center shadow-2xl group-hover:rotate-6 transition duration-500 border-b-4 border-blue-100">
                            <span class="text-blue-600 font-black text-4xl italic" style="font-family:'Syne',sans-serif">L</span>
                        </div>
                        <div class="mt-6">
                            <h2 class="text-3xl font-black tracking-tighter lowercase italic" style="font-family:'Syne',sans-serif">lucky<span class="text-blue-200">draw</span></h2>
                            <p class="text-[9px] font-black tracking-[0.4em] uppercase opacity-60 mt-2">Security Protocol v4.0</p>
                        </div>
                    </a>
                    
                    <div class="max-w-xs mx-auto">
                        <p class="text-sm font-medium leading-relaxed opacity-80 italic">Unified access terminal for premium digital assets and exclusive lucky draw participation.</p>
                    </div>
                </div>

                {{-- Footer Branding on Left Panel --}}
                <div class="absolute bottom-6 left-0 right-0 text-center opacity-30 text-[9px] font-black uppercase tracking-widest hidden md:block">
                    Creator Here | Designer Here
                </div>
            </div>

            {{-- Divider: SVG Wave (Desktop: vertical, Mobile: horizontal) --}}
            <div class="relative z-20 md:w-0">
                {{-- Desktop Vertical Wave --}}
                <div class="hidden md:block absolute top-0 bottom-0 -left-1 w-12 h-full">
                    <svg class="h-full w-full fill-white" preserveAspectRatio="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 C20,20 20,40 0,60 C20,80 20,100 0,100 L100,100 L100,0 Z"></path>
                    </svg>
                </div>
                {{-- Mobile Horizontal Wave --}}
                <div class="md:hidden -mt-1 w-full h-12 bg-white">
                    <svg class="w-full h-full fill-blue-500" preserveAspectRatio="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 C20,80 40,80 60,0 C80,-80 100,0 100,0 L100,100 L0,100 Z" transform="rotate(180 50 50)"></path>
                    </svg>
                </div>
            </div>

            {{-- Right/Bottom Panel: Auth Form --}}
            <div class="flex-1 bg-white p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                <div class="w-full max-w-md mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>