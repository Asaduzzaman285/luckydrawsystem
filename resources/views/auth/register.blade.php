<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Identity')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <x-text-input id="name" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" placeholder="Enter Full Name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-6">
            <x-input-label for="phone" :value="__('Phone Number')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <x-text-input id="phone" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="text" name="phone" :value="old('phone')" required placeholder="e.g. 01711223344" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- District -->
        <div class="mt-6">
            <x-input-label for="district_id" :value="__('Regional Sector')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <select id="district_id" name="district_id" onchange="fetchUpazillas(this.value)" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner appearance-none text-sm" required>
                <option value="">{{ __('Select District') }}</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
        </div>

        <!-- Upazilla -->
        <div class="mt-6">
            <x-input-label for="upazilla_id" :value="__('Local Node')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <select id="upazilla_id" name="upazilla_id" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner appearance-none text-sm" required>
                <option value="">{{ __('Select Upazilla') }}</option>
            </select>
            <x-input-error :messages="$errors->get('upazilla_id')" class="mt-2" />
        </div>

        <script>
            function fetchUpazillas(districtId) {
                const upazillaSelect = document.getElementById('upazilla_id');
                upazillaSelect.innerHTML = '<option value="">{{ __("Scanning Nodes...") }}</option>';
                
                if (!districtId) {
                    upazillaSelect.innerHTML = '<option value="">{{ __("Select Upazilla") }}</option>';
                    return;
                }

                fetch(`/districts/${districtId}/upazillas`)
                    .then(response => response.json())
                    .then(data => {
                        upazillaSelect.innerHTML = '<option value="">{{ __("Select Upazilla") }}</option>';
                        data.forEach(upazilla => {
                            const option = document.createElement('option');
                            option.value = upazilla.id;
                            option.text = upazilla.name;
                            upazillaSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching upazillas:', error);
                        upazillaSelect.innerHTML = '<option value="">{{ __("Error loading upazillas") }}</option>';
                    });
            }

            // Populating if district is already selected (e.g., after validation error)
            document.addEventListener('DOMContentLoaded', function() {
                const districtId = document.getElementById('district_id').value;
                if (districtId) {
                    fetchUpazillas(districtId);
                }
            });
        </script>

        <!-- Email Address (Optional) -->
        <div class="mt-6">
            <x-input-label for="email" :value="__('Digital Archive (Optional)')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />
            <x-text-input id="email" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="email" name="email" :value="old('email')"
                autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-6">
            <x-input-label for="password" :value="__('Security Key')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />

            <x-text-input id="password" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="password" name="password" required
                autocomplete="new-password" placeholder="Create Password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-6">
            <x-input-label for="password_confirmation" :value="__('Verify Security Key')" class="uppercase tracking-widest text-[10px] font-black text-slate-400 mb-2" />

            <x-text-input id="password_confirmation" class="block w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition shadow-inner" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-10">
            <x-primary-button class="w-full justify-center py-5 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition duration-300 uppercase tracking-widest text-xs italic">
                {{ __('Initialize Member Account') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition" href="{{ route('login') }}">
                {{ __('Existing Node? Authenticate') }}
            </a>
        </div>

        <div class="mt-10 pt-8 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                By joining, you agree to our
                <a href="#" class="text-blue-600 font-black hover:underline italic ml-1">Terms of Service</a>
            </p>
        </div>
    </form>
</x-guest-layout>