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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 auth-grid px-4">
        <div class="mb-10">
            <a href="/" class="flex items-center space-x-4 group">
                <div
                    class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:rotate-6 transition duration-300 border border-white/20">
                    <span class="text-white font-black text-3xl italic">L</span>
                </div>
                <div>
                    <div class="text-white font-black text-3xl tracking-tighter lowercase italic">lucky<span
                            class="text-blue-400">draw</span></div>
                    <div class="text-blue-400/40 text-[10px] font-black tracking-[0.3em] uppercase">Security Protocol v4.0
                    </div>
                </div>
            </a>
        </div>

        <div
            class="w-full {{ $maxWidth ?? 'sm:max-w-md' }} bg-white rounded-[2.5rem] p-10 shadow-2xl border border-white/10 overflow-hidden">
            {{ $slot }}
        </div>

        <div class="mt-10 text-white/30 text-[10px] font-black uppercase tracking-widest italic">
            &copy; 2026 LuckyDraw Pro. Secure Access Terminal
        </div>
    </div>
</body>

</html>