<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        @if(auth()->user()->hasRole('agent'))
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <p class="text-xs font-bold text-blue-600 mb-4 tracking-widest uppercase italic">🔐 Security Verification Required</p>
                
                <div class="space-y-4">
                    <x-input-label for="otp_code" :value="__('6-Digit Verification Code')" />
                    <div class="flex gap-2">
                        <x-text-input id="otp_code" name="otp_code" type="text" class="block w-full text-center font-black tracking-[0.5em]" placeholder="000000" maxlength="6" />
                        <button type="button" 
                                onclick="event.preventDefault(); document.getElementById('pw-otp-resend').submit();"
                                class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase tracking-widest hover:bg-slate-700 transition italic shrink-0">
                            Get Code
                        </button>
                    </div>
                    <x-input-error :messages="$errors->updatePassword->get('otp_code')" class="mt-2" />
                    <p class="text-[9px] text-blue-400 font-bold italic mt-1">Codes are sent to your terminal/logs for security.</p>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
