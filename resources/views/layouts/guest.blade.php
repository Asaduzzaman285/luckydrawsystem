<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LuckyDraw Pro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .auth-grid {
            background: radial-gradient(circle at top right, #1e1b4b 0%, #0f172a 100%);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 auth-grid">
        <div class="mb-8">
            <a href="/" class="flex items-center space-x-3 group">
                <div
                    class="w-16 h-16 bg-amber-400 rounded-2xl flex items-center justify-center shadow-2xl group-hover:rotate-6 transition duration-300">
                    <span class="text-slate-900 font-bold text-3xl">L</span>
                </div>
                <div>
                    <div class="text-white font-black text-3xl tracking-tighter lowercase italic">lucky<span
                            class="text-amber-400">draw</span></div>
                    <div class="text-amber-400/40 text-[10px] font-black tracking-[0.3em] uppercase">Security Protocol v4.0
                    </div>
                </div>
            </a>
        </div>

        <div
            class="w-full {{ $maxWidth ?? 'sm:max-w-md' }} mt-6 px-10 py-10 bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden sm:rounded-3xl">
            {{ $slot }}
        </div>

        <div class="mt-8 text-white/30 text-sm font-medium">
            &copy; 2026 LuckyDraw Pro. All rights reserved.
        </div>
    </div>
</body>

</html>