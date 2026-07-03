<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24">
        <!-- Header Section -->
        <div class="bg-blue-600 rounded-b-[3rem] p-8 pt-12 shadow-2xl relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center text-white backdrop-blur-sm hover:bg-white/20 transition">
                        <span class="text-lg">←</span>
                    </a>
                    <div>
                        <div class="text-[10px] font-black text-white/60 uppercase tracking-widest italic">Account Settings</div>
                        <h1 class="text-2xl font-black text-white tracking-tight italic">My Profile</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 md:px-8 max-w-7xl mx-auto space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Info Update Form -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-white">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Password Update Form -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-white">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account Form -->
            <div class="bg-red-50 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-red-900/5 border border-red-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
