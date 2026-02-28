<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LuckyDraw Pro - Premium Lottery & Draw System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .bg-royal-blue {
            background-color: #0f172a;
        }

        .text-gold {
            color: #fbbf24;
        }

        .border-gold {
            border-color: #fbbf24;
        }

        .bg-gold {
            background-color: #fbbf24;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
        }
    </style>
</head>

<body class="bg-slate-950 text-white antialiased overflow-x-hidden selection:bg-amber-400 selection:text-slate-900">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-royal-blue/80 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-royal-blue font-bold text-xl">L</span>
                </div>
                <span class="text-2xl font-black tracking-tighter lowercase italic">lucky<span class="text-amber-400">draw</span></span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#how-it-works" class="text-gray-300 hover:text-gold transition">How It Works</a>
                <a href="#winners" class="text-gray-300 hover:text-gold transition">Latest Winners</a>
                @if (Route::has('login'))
                    @if (auth()->check())
                        <a href="{{ url('/dashboard') }}"
                            class="px-6 py-2 bg-gold text-royal-blue rounded-full font-bold hover:scale-105 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-gold transition">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-6 py-2 bg-gold text-royal-blue rounded-full font-bold hover:scale-105 transition">Join
                            Now</a>
                    @endif
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-20">
        <div class="max-w-7xl mx-auto px-6">
            @if(session('status'))
                <div class="mb-8 p-4 bg-amber-400/10 border border-amber-400/20 rounded-2xl text-amber-400 font-bold flex items-center space-x-3 animate-fade-in">
                    <span>👋</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 animate-fade-in">
                <div
                    class="inline-block px-4 py-1 bg-blue-500/10 border border-blue-400/20 rounded-full text-blue-400 text-sm font-semibold tracking-wide">
                    ✨ THE MOST TRUSTED DRAW PLATFORM
                </div>
                <h1 class="text-6xl lg:text-8xl font-black text-white leading-[0.9] tracking-tighter lowercase italic mb-6">
                    luck into <br>
                    <span class="text-amber-400">reality.</span>
                </h1>
                <p class="text-gray-400 text-xl max-w-xl leading-relaxed">
                    Experience the gold standard of online draws. Verifiable transparency, instant payouts, and huge
                    prizes waiting for you every single day.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 pt-4">
                    <a href="{{ route('register') }}"
                        class="px-8 py-4 bg-gold text-royal-blue rounded-xl font-bold text-lg hover:scale-105 transition shadow-2xl flex items-center justify-center">
                        Start Drawing Today
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#how-it-works"
                        class="px-8 py-4 bg-white/5 border border-white/10 text-white rounded-xl font-bold text-lg hover:bg-white/10 transition flex items-center justify-center">
                        Learn More
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <!-- Glassmorphism Card Preview -->
                <div
                    class="bg-white/5 backdrop-blur-3xl border border-white/10 rounded-3xl p-8 shadow-2xl skew-y-3 transform hover:rotate-0 transition duration-700">
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-gold font-bold">LIVE DRAWS</span>
                        <span class="w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                    </div>

                    <div class="space-y-6">
                        <div
                            class="bg-white/10 p-4 rounded-2xl flex justify-between items-center border border-white/5 cursor-default hover:bg-white/20 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gold rounded-xl flex items-center justify-center text-2xl">🏆
                                </div>
                                <div>
                                    <div class="text-white font-semibold">Mega Jackpot #402</div>
                                    <div class="text-gray-400 text-sm">Prize: $50,000.00</div>
                                </div>
                            </div>
                            <div class="text-gold font-bold">Ends in 2h</div>
                        </div>

                        <div
                            class="bg-white/10 p-4 rounded-2xl flex justify-between items-center border border-white/5 opacity-80">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-2xl">
                                    💎</div>
                                <div>
                                    <div class="text-white font-semibold">Emerald Draw</div>
                                    <div class="text-gray-400 text-sm">Prize: $2,500.00</div>
                                </div>
                            </div>
                            <div class="text-gray-400 font-bold">Soon</div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600 rounded-full blur-[100px] opacity-20">
                </div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gold rounded-full blur-[100px] opacity-10"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gold mb-2">$2.4M+</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Prizes Awarded</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-gold mb-2">150K+</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Active Members</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-gold mb-2">100%</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Verifiable Fairness</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-gold mb-2">24/7</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Global Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5">
        <div
            class="max-w-7xl mx-auto px-6 flex flex-col md:row-reverse md:flex-row justify-between items-center space-y-6 md:space-y-0">
            <div class="flex space-x-6 text-gray-400 text-sm">
                <a href="#" class="hover:text-gold">Privacy Policy</a>
                <a href="#" class="hover:text-gold">Terms of Service</a>
                <a href="#" class="hover:text-gold">Contact Us</a>
            </div>
            <div class="text-gray-500 text-sm">
                &copy; 2026 LuckyDraw Pro. Designed for Champions.
            </div>
        </div>
    </footer>
</body>

</html>