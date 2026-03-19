<x-guest-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-800 italic leading-none mb-3" style="font-family:'Syne',sans-serif">
            Welcome / <span class="text-blue-600">Back</span>
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 italic font-bold text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Phone or Email Address -->
        <div class="group">
            <label for="login" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 transition-colors group-focus-within:text-blue-600">
                Phone or Email
            </label>
            <div class="relative">
                <input id="login" type="text" name="login" :value="old('login')" required autofocus 
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-3 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="Enter phone or email" />
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="group">
            <div class="flex justify-between items-end mb-1.5">
                <label for="password" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 transition-colors group-focus-within:text-blue-600">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a class="text-[9px] font-black uppercase tracking-widest text-slate-300 hover:text-blue-600 transition" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-3 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="Enter password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="w-4 h-4 rounded-md border-2 border-slate-200 text-blue-600 focus:ring-blue-500/20 transition cursor-pointer">
                </div>
                <span class="ms-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-600 transition">
                    Remember Me
                </span>
            </label>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-4">
            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 uppercase tracking-widest text-[11px] italic">
                Sign In
            </button>
            <a href="{{ route('register') }}" class="flex-1 bg-slate-50 text-slate-400 font-black py-4 rounded-2xl border border-slate-100 text-center hover:bg-white hover:border-blue-200 hover:text-blue-600 transition-all duration-300 uppercase tracking-widest text-[10px] italic">
                Create Account
            </a>
        </div>

        <div class="text-center pt-8 border-t border-slate-50">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">
                LuckyDraw Pro / v4.0
            </p>
        </div>
    </form>
</x-guest-layout>