<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight">
            {{ __('Agent') }} <span class="text-amber-500">Operation Portal</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-xl border-l-4 border-amber-400">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Volume Processed
                    </div>
                    <div class="mt-1 text-3xl font-bold text-slate-900">
                        ${{ number_format($stats['total_deposits'], 2) }}</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl border-l-4 border-slate-900">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Onboarded Members</div>
                    <div class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['users_created'] }}</div>
                </div>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
                    <span class="mr-2">✅</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Create User Form -->
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-100 italic">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                            <span class="text-2xl">👤</span>
                            <h3 class="text-xl font-bold text-slate-900">Register <span class="text-amber-500">New
                                    Member</span></h3>
                        </div>
                        <form action="{{ route('agent.users.store') }}" method="POST">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Full Name</label>
                                    <input type="text" name="name"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="Enter legal name" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Email Identifier</label>
                                    <input type="email" name="email"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="user@example.com" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Secure Password</label>
                                    <input type="password" name="password"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="••••••••" required>
                                </div>
                                <button type="submit"
                                    class="w-full bg-slate-900 hover:bg-amber-400 hover:text-slate-900 text-white font-black py-4 px-4 rounded-xl shadow-xl transition duration-300 uppercase tracking-widest text-sm">
                                    Authorize Registration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Process Deposit Form -->
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-100 italic">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                            <span class="text-2xl">💰</span>
                            <h3 class="text-xl font-bold text-slate-900">Capital <span
                                    class="text-amber-500">Deposit</span></h3>
                        </div>
                        <form action="{{ route('agent.deposit.store') }}" method="POST">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Member ID</label>
                                    <input type="number" name="user_id"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="Enter Member System ID" required>
                                    <p class="mt-2 text-[10px] text-amber-600 font-bold uppercase tracking-tighter">
                                        Verified member ID required for funding</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Transaction Amount
                                        ($)</label>
                                    <input type="number" step="0.01" name="amount"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="0.00" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-500 mb-1">Approval
                                        Reference</label>
                                    <input type="text" name="reference_id"
                                        class="bg-slate-50 border-slate-200 text-slate-900 focus:border-amber-400 focus:ring-amber-400 rounded-xl w-full py-3 px-4 shadow-sm transition"
                                        placeholder="Optional transaction ref">
                                </div>
                                <button type="submit"
                                    class="w-full bg-amber-400 hover:bg-slate-900 hover:text-white text-slate-900 font-black py-4 px-4 rounded-xl shadow-xl transition duration-300 uppercase tracking-widest text-sm">
                                    Finalize Credit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Traceability Info -->
            <div class="bg-slate-900 p-8 rounded-3xl shadow-2xl border border-white/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400 opacity-5 blur-3xl rounded-full"></div>
                <div class="relative z-10 flex items-center space-x-6">
                    <div
                        class="w-16 h-16 bg-amber-400/10 rounded-2xl flex items-center justify-center border border-amber-400/20 text-3xl">
                        🛡️</div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1 uppercase tracking-tight">Security & <span
                                class="text-amber-400">Traceability Protocol</span></h3>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-2xl font-medium">
                            System Sentinel Active. Every transaction and registration processed by this terminal is
                            cryptographically linked to your agent signature <b>(HEX: {{ auth()->id() }})</b>. Complete
                            audit transparency is maintained by the Administration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>