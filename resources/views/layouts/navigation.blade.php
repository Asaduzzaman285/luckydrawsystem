<nav x-data="{ open: false }" class="bg-[#1e3a8a] border-b border-white/10 relative z-50 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                        <div
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-12 transition shrink-0 font-black italic text-white border border-white/20">
                            L
                        </div>
                        <span class="text-white font-black text-xl tracking-tighter lowercase italic">lucky<span
                                class="text-blue-400">draw</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center text-nowrap">
                    @php
                        $dashboardRoute = route('dashboard');
                        if (Auth::user()->hasRole('agent')) {
                            $dashboardRoute = route('agent.dashboard');
                        } elseif (Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
                            $dashboardRoute = route('admin.dashboard');
                        }
                    @endphp
                    <x-nav-link :href="$dashboardRoute" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('agent.dashboard')"
                        class="text-white/70 hover:text-white hover:border-blue-400 transition font-bold uppercase tracking-tighter text-[9px]">
                        @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                            {{ __('Admin Hub') }}
                        @elseif(Auth::user()->hasRole('agent'))
                            {{ __('Agent Hub') }}
                        @else
                            {{ __('Member Hub') }}
                        @endif
                    </x-nav-link>

                    <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Results') }}
                    </x-nav-link>

                    @can('create-draw')
                    <x-nav-link :href="route('draws.index')" :active="request()->routeIs('draws.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Draws') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Products') }}
                    </x-nav-link>
                    @endcan

                    @can('approve-withdrawal')
                    <x-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Withdrawals') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Staff') }}
                    </x-nav-link>
                    @endcan

                    @can('deposit-to-user')
                    <x-nav-link :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Agent Portal') }}
                    </x-nav-link>
                    <x-nav-link :href="route('agent.prizes.index')" :active="request()->routeIs('agent.prizes.*')" class="text-white/70 hover:text-white transition font-bold uppercase tracking-tighter text-[9px]">
                        {{ __('Prize Handover') }}
                    </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-white/10 text-xs leading-4 font-black rounded-xl text-white bg-white/5 hover:bg-white/10 transition ease-in-out duration-150">
                            <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center text-[10px] text-white font-black mr-3 shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="tracking-tight">{{ Auth::user()->name }}</div>

                            <div class="ms-1 opacity-40">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-blue-50">
                            {{ __('My Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50 font-bold" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Secure Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white/70 hover:text-white hover:bg-white/5 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#1e3a8a] border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-white/70 hover:text-white hover:bg-white/5">
                @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                    {{ __('Admin Hub') }}
                @elseif(Auth::user()->hasRole('agent'))
                    {{ __('Agent Hub') }}
                @else
                    {{ __('Member Hub') }}
                @endif
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.index')"
                class="text-white/70 hover:text-white">
                {{ __('Draw Results') }}
            </x-responsive-nav-link>

            @can('create-draw')
            <x-responsive-nav-link :href="route('draws.index')" :active="request()->routeIs('draws.*')"
                class="text-white/70 hover:text-white">
                {{ __('Draws') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"
                class="text-white/70 hover:text-white">
                {{ __('Products') }}
            </x-responsive-nav-link>
            @endcan

            @can('approve-withdrawal')
            <x-responsive-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')"
                class="text-white/70 hover:text-white">
                {{ __('Withdrawals') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                class="text-white/70 hover:text-white">
                {{ __('Staff Management') }}
            </x-responsive-nav-link>
            @endcan

            @can('deposit-to-user')
            <x-responsive-nav-link :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')"
                class="text-white/70 hover:text-white">
                {{ __('Agent Portal') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('agent.prizes.index')" :active="request()->routeIs('agent.prizes.*')"
                class="text-white/70 hover:text-white">
                {{ __('Prize Handover') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/50">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white/70">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-red-400" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>