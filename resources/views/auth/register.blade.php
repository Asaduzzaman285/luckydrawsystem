<x-guest-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-800 italic leading-none mb-3" style="font-family:'Syne',sans-serif">
            Create / <span class="text-blue-600">Account</span>
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Join our lucky draw community</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Name -->
            <div class="group">
                <label for="name" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    Full Name
                </label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="Enter full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Phone -->
            <div class="group">
                <label for="phone" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    Phone Number
                </label>
                <input id="phone" type="text" name="phone" :value="old('phone')" required
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="e.g. 01711223344" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- District -->
            <div class="group">
                <label for="district_id" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    District
                </label>
                <select id="district_id" name="district_id" onchange="fetchUpazillas(this.value)" 
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 focus:ring-0 focus:border-blue-600 transition-all duration-300 appearance-none cursor-pointer">
                    <option value="">Select District</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('district_id')" class="mt-1" />
            </div>

            <!-- Upazilla -->
            <div class="group">
                <label for="upazilla_id" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    Upazilla
                </label>
                <select id="upazilla_id" name="upazilla_id" 
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 focus:ring-0 focus:border-blue-600 transition-all duration-300 appearance-none cursor-pointer">
                    <option value="">Select Upazilla</option>
                </select>
                <x-input-error :messages="$errors->get('upazilla_id')" class="mt-1" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="group">
            <label for="email" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                Email Address (Optional)
            </label>
            <input id="email" type="email" name="email" :value="old('email')" autocomplete="username"
                class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Password -->
            <div class="group">
                <label for="password" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="Create password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="group">
                <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 transition-colors group-focus-within:text-blue-600">
                    Confirm Password
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full border-0 border-b-2 border-slate-100 bg-transparent py-2.5 px-0 font-bold text-slate-800 placeholder-slate-300 focus:ring-0 focus:border-blue-600 transition-all duration-300" 
                    placeholder="Verify password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="pt-4 space-y-4">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 uppercase tracking-widest text-[11px] italic">
                Create Account
            </button>
            <div class="text-center">
                <a class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition" href="{{ route('login') }}">
                    Already registered? Sign In
                </a>
            </div>
        </div>

        <div class="text-center pt-6 border-t border-slate-50">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-loose">
                By joining, you agree to our 
                <a href="#" class="text-blue-600 underline">Terms of Service</a>
            </p>
        </div>
    </form>

    <script>
        function fetchUpazillas(districtId) {
            const upazillaSelect = document.getElementById('upazilla_id');
            upazillaSelect.innerHTML = '<option value="">Loading...</option>';
            if (!districtId) {
                upazillaSelect.innerHTML = '<option value="">Select Upazilla</option>';
                return;
            }
            fetch(`/districts/${districtId}/upazillas`)
                .then(response => response.json())
                .then(data => {
                    upazillaSelect.innerHTML = '<option value="">Select Upazilla</option>';
                    data.forEach(upazilla => {
                        const option = document.createElement('option');
                        option.value = upazilla.id;
                        option.text = upazilla.name;
                        upazillaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    upazillaSelect.innerHTML = '<option value="">Error loading</option>';
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const districtId = document.getElementById('district_id').value;
            if (districtId) fetchUpazillas(districtId);
        });
    </script>
</x-guest-layout>