<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-[3rem] p-12 shadow-2xl border border-slate-100 relative overflow-hidden">
            <!-- Branding Accent -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

            <div class="mb-10 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6 shadow-inner">
                    🔐
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter lowercase italic">secure / <span class="text-blue-600">access</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-3">6-Digit Multi-Factor Authentication</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-600 text-[10px] font-black uppercase italic text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('debug_otp'))
                <div class="mb-6 p-4 bg-amber-50 border border-amber-100 rounded-2xl text-amber-700 text-[10px] font-black uppercase italic text-center">
                    {{ session('debug_otp') }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="code" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic text-center">Enter Verification Code</label>
                    <input id="code" type="text" name="code" 
                           class="w-full bg-slate-50 border-slate-200 rounded-2xl py-6 px-4 text-3xl font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition text-center tracking-[0.5em] shadow-inner" 
                           placeholder="000000" maxlength="6" required autofocus autocomplete="one-time-code">
                    <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white text-[11px] font-black py-5 rounded-2xl uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all transform hover:scale-[1.02] italic">
                        Authorize Terminal
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-50 text-center">
                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Didn't receive the code?</p>
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-[10px] font-black uppercase tracking-widest transition italic">
                        Request New OTP
                    </button>
                </form>
            </div>
            
            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-300 hover:text-slate-500 text-[9px] font-black uppercase tracking-widest transition">
                        Cancel & Logout
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-10 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center space-x-2">
            <span>🛡️</span>
            <span>military-grade encryption protocols active</span>
        </p>
    </div>
</x-guest-layout>
