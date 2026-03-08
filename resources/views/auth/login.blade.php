<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 italic font-bold" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Phone or Email Address -->
        <div>
            <x-input-label for="login" :value="__('Security Identifier')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <x-text-input id="login" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="text" name="login" :value="old('login')" required
                autofocus autocomplete="username" placeholder="Phone or Email" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-6">
            <x-input-label for="password" :value="__('Access Protocol')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />

            <x-text-input id="password" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="password" name="password" required
                autocomplete="current-password" placeholder="Password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-6">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded-lg border-slate-200 text-blue-600 shadow-sm focus:ring-blue-500 w-5 h-5 transition" name="remember">
                <span class="ms-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-600 transition">{{ __('Persistent Session') }}</span>
            </label>
        </div>

        <div class="mt-10">
            <x-primary-button class="w-full justify-center py-5 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition duration-300 uppercase tracking-widest text-xs italic">
                {{ __('Authorize Access') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-center mt-6">
            @if (Route::has('password.request'))
                <a class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition" href="{{ route('password.request') }}">
                    {{ __('Recover Credentials') }}
                </a>
            @endif
        </div>

        <div class="mt-10 pt-8 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                System Interface /
                <a href="{{ route('register') }}" class="text-blue-600 font-black hover:underline italic ml-1">Initialize Account</a>
            </p>
        </div>
    </form>
</x-guest-layout>