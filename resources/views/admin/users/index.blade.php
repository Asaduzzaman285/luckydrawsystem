<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">staff</a>
                    <span>/</span>
                    <span class="text-blue-600">management</span>
                </nav>
                <h1 class="text-pro-title">personnel registry</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Manage System Administrators and Regional Distribution
                    Agents</p>
            </div>
            <button @click="$dispatch('open-create-modal')"
                class="btn-pro-primary !bg-blue-600 !text-white !shadow-blue-500/20">
                <span class="mr-2 text-lg">+</span> Appoint Staff
            </button>
        </div>
    </x-slot>

    <div class="pb-24" x-data="{ creditModal: false, createModal: false, selectedUser: null, selectedUserName: '' }"
        @open-create-modal.window="createModal = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div
                    class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-600 font-bold flex items-center space-x-3 animate-fade-in text-xs uppercase italic">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Total Users</span>
                    <div class="stats-value text-white">{{ $stats['total_users'] }}</div>
                    <span class="stats-subtext">Entire database</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Admins</span>
                    <div class="stats-value text-amber-400">{{ $stats['admins'] }}</div>
                    <span class="stats-subtext">Controllers</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Agents</span>
                    <div class="stats-value text-blue-300">{{ $stats['agents'] }}</div>
                    <span class="stats-subtext">Regional operators</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Members</span>
                    <div class="stats-value text-emerald-400">{{ $stats['members'] }}</div>
                    <span class="stats-subtext">Participants</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Wallet Pool</span>
                    <div class="stats-value text-white"><span
                            class="text-amber-400 mr-1">৳</span>{{ number_format($stats['total_wallets_balance'], 0) }}
                    </div>
                    <span class="stats-subtext">Total liquidity</span>
                </div>
            </div>

            <!-- Role Filtering Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('users.index', ['role' => 'all']) }}"
                    class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ $roleFilter === 'all' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-white text-slate-400 border border-slate-100 hover:bg-slate-50' }}">
                    All Registry
                </a>
                <a href="{{ route('users.index', ['role' => 'staff']) }}"
                    class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ $roleFilter === 'staff' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-white text-slate-400 border border-slate-100 hover:bg-slate-50' }}">
                    Staff Only
                </a>
                <a href="{{ route('users.index', ['role' => 'members']) }}"
                    class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ $roleFilter === 'members' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-white text-slate-400 border border-slate-100 hover:bg-slate-50' }}">
                    Members Only
                </a>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100 text-slate-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Name & Role</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Location</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Assigned Agent</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Wallet Balance</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Status</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xs font-black text-blue-600 border border-blue-100 italic">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 tracking-tight italic">
                                                    {{ $user->name }}</div>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span
                                                        class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                        @if($user->hasRole('super-admin'))
                                                            <span class="text-amber-500">Super Admin</span>
                                                        @elseif($user->hasRole('admin'))
                                                            <span class="text-blue-600">Administrator</span>
                                                        @else
                                                            <span class="text-slate-500">Agent</span>
                                                        @endif
                                                    </span>
                                                    <span class="text-[10px] text-slate-300">•</span>
                                                    <span
                                                        class="text-[10px] text-blue-600/80 font-black italic">{{ $user->phone }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-black text-slate-900 tracking-tighter">
                                            {{ $user->district->name ?? 'Global' }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold mt-1">
                                            {{ $user->upazilla->name ?? '--' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($user->agent)
                                            <div class="font-black text-blue-600 text-xs tracking-tight italic">
                                                {{ $user->agent->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold mt-0.5">{{ $user->agent->phone }}
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">No
                                                Agent</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">৳
                                            {{ number_format($user->wallet->balance ?? 0, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="badge-dot {{ $user->is_active ?? true ? 'badge-dot-live' : 'badge-dot-ended' }}">
                                            <span
                                                class="dot {{ $user->is_active ?? true ? 'dot-live' : 'dot-ended' }}"></span>
                                            {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            @if($user->hasRole(['admin', 'agent']))
                                                @if(auth()->user()->hasRole('super-admin') || !$user->hasRole(['admin', 'super-admin']))
                                                    <button
                                                        @click="creditModal = true; selectedUser = {{ $user->id }}; selectedUserName = '{{ $user->name }}'"
                                                        class="btn-pro-action transition">
                                                        <span class="mr-1.5">+</span> Fund
                                                    </button>
                                                @endif
                                            @endif

                                            @if(auth()->user()->hasRole('super-admin') || (!$user->hasRole('admin') && !$user->hasRole('super-admin')))
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn-pro-secondary hover:!bg-blue-600 hover:!text-white hover:!border-blue-600 transition tracking-widest text-[9px]">Configure</a>
                                            @endif

                                            @role('super-admin')
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('EXTERMINATION PROTOCOL: Are you sure you want to delete this user?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-400 hover:text-red-600 transition transform hover:scale-110">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif

                <!-- Create Staff Modal -->
                <div x-show="createModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-hidden"
                    x-cloak>
                    <div
                        class="bg-white w-full max-w-2xl rounded-[3rem] p-8 md:p-12 shadow-2xl relative border border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                        <button @click="createModal = false"
                            class="absolute top-10 right-10 text-slate-400 hover:text-slate-900 transition font-black text-xl">✕</button>
                        <div class="mb-10 text-center text-slate-900">
                            <h3 class="text-3xl font-black tracking-tighter lowercase italic">appoint / <span
                                    class="text-blue-600">staff</span></h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">New
                                Distribution Protocol Initiation</p>
                        </div>
                        <form action="{{ route('users.store') }}" method="POST" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full
                                        Identity</label>
                                    <input type="text" name="name"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition"
                                        placeholder="e.g. John Agent" required>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone
                                        Number</label>
                                    <input type="text" name="phone"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition"
                                        placeholder="e.g. 01711223344" required>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email
                                        Address (Optional)</label>
                                    <input type="email" name="email"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition"
                                        placeholder="e.g. john@luckydraw.com">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                                    <input type="password" name="password"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600"
                                        required>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600"
                                        required>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ selectedRole: 'agent' }">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deployment
                                        Role</label>
                                    <select name="role" x-model="selectedRole"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm">
                                        <option value="agent">Regional Agent</option>
                                        @if(auth()->user()->hasRole('super-admin'))
                                            <option value="admin">System Administrator</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="space-y-2" x-show="selectedRole === 'agent'">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned
                                        District</label>
                                    <select id="modal_district_id" name="district_id"
                                        onchange="fetchModalUpazillas(this.value)"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm">
                                        <option value="">Global / Unassigned</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2" x-show="selectedRole === 'agent'">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Regional
                                        Upazilla</label>
                                    <select id="modal_upazilla_id" name="upazilla_id"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm">
                                        <option value="">Select Upazilla</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-[1.02] transition uppercase tracking-widest text-xs italic">
                                Authorize Account
                            </button>
                        </form>
                        <script>
                            function fetchModalUpazillas(districtId) {
                                const upazillaSelect = document.getElementById('modal_upazilla_id');
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
                                    });
                            }
                        </script>
                    </div>
                </div>

                <!-- Funding Modal -->
                <div x-show="creditModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-hidden"
                    x-cloak>
                    <div
                        class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                        <button @click="creditModal = false"
                            class="absolute top-8 right-8 text-slate-400 hover:text-slate-900 transition font-black">✕</button>
                        <div class="mb-8 text-slate-900">
                            <h3 class="text-2xl font-black tracking-tighter lowercase italic">fund / <span
                                    class="text-blue-600">wallet</span></h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Dispensing
                                credit to: <span class="text-blue-600 font-black italic"
                                    x-text="selectedUserName"></span></p>
                        </div>
                        <form :action="'{{ url('/users') }}/' + selectedUser + '/credit'" method="POST"
                            class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Funding
                                    Amount</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="amount"
                                        class="w-full bg-slate-50 border-slate-200 rounded-2xl py-5 px-6 text-xl font-black text-slate-900 focus:ring-blue-600"
                                        placeholder="0.00" required>
                                    <span
                                        class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">BDT</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Reference
                                    / Note</label>
                                <input type="text" name="reference"
                                    class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600"
                                    placeholder="e.g. Batch #102">
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 hover:scale-[1.02] transition duration-300 uppercase tracking-widest text-xs italic">
                                Confirm Funding
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>