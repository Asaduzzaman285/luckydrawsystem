<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24"
        x-data="{ 
            registerModal: false, 
            depositModal: false, 
            payoutModal: false,
            searchQuery: '',
            selectedUser: null,
            managedUsers: {{ $managedUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'phone' => $u->phone])->toJson() }},
            get filteredUsers() {
                if (!this.searchQuery) return [];
                return this.managedUsers.filter(u => 
                    u.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                    u.phone.includes(this.searchQuery)
                );
            }
        }"
        x-on:open-modal.window="
            if ($event.detail === 'register-member') registerModal = true;
            if ($event.detail === 'deposit-fund') depositModal = true;
        ">
        
        <!-- Professional Header -->
        <div class="bg-gradient-to-r from-[#1a56db] to-[#1e3a8a] pt-8 pb-20 px-4 sm:px-8 shadow-inner">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center text-white font-black text-xl italic shadow-lg">
                            A
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">agent terminal</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none tracking-tighter">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Balance Toggle Pill (Agent Earnings) -->
                    <div x-data="{ show: false }" @click="show = !show" class="balance-pill group scale-90 sm:scale-100 origin-right border-white/10 bg-white/10 backdrop-blur-md">
                        <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-black italic shadow-lg">৳</div>
                        <div class="relative flex-1 overflow-hidden h-5 ml-2">
                            <div x-show="!show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-[10px] font-black text-white uppercase tracking-widest pt-0.5 italic">tap for earnings</div>
                            <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-sm font-black text-white tracking-tighter pt-0">৳ {{ number_format($stats['wallet_balance'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Service Grid ("Box Box") -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 mb-8 border border-white text-center">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <button @click="registerModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-xl sm:text-2xl text-blue-600 transition-all duration-300 shadow-sm border border-blue-100/50 group-hover:bg-blue-600 group-hover:text-white">👤</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 group-hover:text-blue-600">add user</span>
                    </button>
                    <button @click="depositModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-xl sm:text-2xl text-emerald-600 transition-all duration-300 shadow-sm border border-emerald-100/50 group-hover:bg-emerald-600 group-hover:text-white">💸</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 group-hover:text-emerald-600">add money</span>
                    </button>
                    <button @click="payoutModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl sm:text-2xl text-indigo-600 transition-all duration-300 shadow-sm border border-indigo-100/50 group-hover:bg-indigo-600 group-hover:text-white">🏦</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 group-hover:text-indigo-600">withdraw</span>
                    </button>
                    <a href="{{ route('agent.prizes.index') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-xl sm:text-2xl text-amber-500 transition-all duration-300 shadow-sm border border-amber-100/50 group-hover:bg-amber-500 group-hover:text-white">🏆</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 group-hover:text-amber-500">prizes</span>
                    </a>
                </div>
            </div>

            <!-- Agent Growth Hero -->
            <div class="mb-10">
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 relative p-8 sm:p-12 text-slate-900 border border-white">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div>
                            <h2 class="text-3xl font-black tracking-tighter italic lowercase mb-4">Operations / <span class="text-blue-600">Summary</span></h2>
                            <div class="flex flex-wrap gap-6 mt-6">
                                <div>
                                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Processed</div>
                                    <div class="text-2xl font-black tracking-tighter italic">৳ {{ number_format($stats['total_deposits'], 2) }}</div>
                                </div>
                                <div class="px-6 border-l border-slate-100">
                                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Users</div>
                                    <div class="text-2xl font-black tracking-tighter text-blue-600 italic">{{ $stats['users_created'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-8 rounded-3xl border border-blue-100 text-center min-w-[240px] shadow-inner">
                            <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">My Commission</div>
                            <div class="text-4xl font-black text-blue-700 tracking-tighter leading-none mb-4 italic">৳ {{ number_format($stats['wallet_balance'], 2) }}</div>
                            <button @click="payoutModal = true" class="w-full bg-blue-600 text-white text-[10px] font-black py-4 rounded-xl uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">Request payout</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Withdrawal Requests (Pending Approval) -->
            @if($pendingUserWithdrawals->isNotEmpty())
            <div class="mb-10">
                <div class="bg-white rounded-[2rem] border border-white overflow-hidden shadow-xl shadow-blue-900/5">
                    <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">user / <span class="text-blue-600">withdrawal requests</span></h3>
                        <span class="bg-blue-600 text-white text-[8px] font-black px-3 py-1 rounded-full animate-pulse">{{ $pendingUserWithdrawals->count() }} PENDING</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Member</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Amount</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Method</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($pendingUserWithdrawals as $req)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-8 py-4">
                                            <div class="font-black text-slate-900 text-xs tracking-tight italic">{{ $req->user->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold mt-1">{{ $req->user->phone }}</div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="text-sm font-black text-blue-600 tracking-tighter italic">৳ {{ number_format($req->amount, 2) }}</div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest">{{ $req->payment_method }}</div>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('agent.withdrawals.approve', $req->id) }}" method="POST" onsubmit="return confirm('Approve this withdrawal? ৳{{ $req->amount }} will be moved from user to your wallet.')">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-[8px] font-black px-3 py-2 rounded-lg uppercase tracking-widest transition shadow-lg shadow-emerald-500/20">Approve</button>
                                                </form>
                                                <form action="{{ route('agent.withdrawals.reject', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-[8px] font-black px-3 py-2 rounded-lg uppercase tracking-widest transition">Reject</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payout Request History -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white text-slate-900 mb-10">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Payout / <span class="text-blue-600 italic font-bold">Log</span></h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Last 10 Records</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Method</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Amount</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Identity Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Age</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($withdrawalRequests as $request)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-4">
                                        <div class="font-black text-slate-900 text-xs tracking-tight">{{ $request->payment_method }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold mt-1 italic">REF: {{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter italic">৳ {{ number_format($request->amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if($request->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-amber-500/10 text-amber-600 uppercase tracking-widest">Awaiting Admin</span>
                                        @elseif($request->status === 'approved' || $request->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-500/10 text-emerald-600 uppercase tracking-widest">Authorized</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-500/10 text-red-600 uppercase tracking-widest">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em]">{{ $request->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                        @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">Zero withdrawal Activity Records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Managed Users & Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Managed Users -->
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white text-slate-900">
                    <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">managed / <span class="text-blue-600 italic font-bold">users</span></h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $managedUsers->count() }} Profiles</p>
                    </div>
                    <div class="overflow-x-auto max-h-[400px]">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Member</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Balance</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($managedUsers as $user)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-8 py-4">
                                            <div class="font-black text-slate-900 text-xs tracking-tight italic">{{ $user->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold mt-1 tracking-widest">{{ $user->phone }}</div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="text-xs font-black text-blue-600 tracking-tighter">৳ {{ number_format($user->wallet->balance ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[7px] font-black {{ $user->is_active ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }} uppercase tracking-widest">
                                                {{ $user->is_active ? 'Active' : 'Locked' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">Zero Member Records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent User Activity (Tickets) -->
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white text-slate-900">
                    <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">user / <span class="text-blue-600 italic font-bold">activity</span></h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Latest Purchases</p>
                    </div>
                    <div class="overflow-x-auto max-h-[400px]">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">User & Product</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentTickets as $ticket)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-8 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center border border-blue-100">
                                                    <span class="text-xs">🎟️</span>
                                                </div>
                                                <div>
                                                    <div class="font-black text-slate-900 text-xs tracking-tight italic">{{ $ticket->user->name }}</div>
                                                    <div class="text-[9px] text-blue-600 font-black uppercase tracking-widest">{{ $ticket->product->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-right whitespace-nowrap">
                                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em]">{{ $ticket->created_at->diffForHumans() }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">Zero Activity Records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div x-show="registerModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-hidden" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                <button @click="registerModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">onboard / <span class="text-blue-600">user</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Create a new member account</p>
                </div>
                <form action="{{ route('agent.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm" placeholder="Full Name" required>
                    <input type="text" name="phone" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm" placeholder="Phone Number" required>
                    <input type="email" name="email" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm" placeholder="Email (Optional)">
                    
                    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                        <div class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Assigned Operational Area</div>
                        <div class="text-xs font-black text-slate-900 italic tracking-tight">
                            {{ Auth::user()->district->name ?? 'N/A' }} / {{ Auth::user()->upazilla->name ?? 'N/A' }}
                        </div>
                        <p class="text-[8px] text-blue-400 font-bold uppercase mt-1">Users are locked to your service region</p>
                    </div>

                    <input type="password" name="password" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-sm" placeholder="Initial Password" required>

                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Finalize Onboarding
                    </button>
                </form>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div x-show="depositModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-hidden" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                <button @click="depositModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">credit / <span class="text-emerald-600">transfer</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Move balance to a member account</p>
                </div>
                <form action="{{ route('agent.deposit.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1 relative" x-data="{ open: false }">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Target Account</label>
                        
                        <!-- Search Input -->
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="searchQuery" 
                                @focus="open = true"
                                @click.away="setTimeout(() => open = false, 200)"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-emerald-400 text-sm" 
                                placeholder="Search by Phone or Name..."
                                autocomplete="off"
                            >
                            <div class="absolute right-6 top-1/2 -translate-y-1/2">
                                <span class="text-xs">🔍</span>
                            </div>
                        </div>

                        <!-- Hidden Input for Form Submission -->
                        <input type="hidden" name="user_id" :value="selectedUser?.id" required>

                        <!-- Selected User Badge -->
                        <template x-if="selectedUser">
                            <div class="mt-2 p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex justify-between items-center animate-fade-in">
                                <div>
                                    <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none">Selected Member</div>
                                    <div class="text-xs font-black text-slate-900 mt-1" x-text="selectedUser.name"></div>
                                    <div class="text-[9px] text-slate-400 font-bold" x-text="selectedUser.phone"></div>
                                </div>
                                <button type="button" @click="selectedUser = null; searchQuery = ''" class="text-slate-400 hover:text-red-500 font-black">✕</button>
                            </div>
                        </template>

                        <!-- Dropdown List -->
                        <div 
                            x-show="open && filteredUsers.length > 0" 
                            class="absolute z-[70] left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 max-h-48 overflow-y-auto"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-end="opacity-0 translate-y-2"
                        >
                            <template x-for="user in filteredUsers" :key="user.id">
                                <div 
                                    @click="selectedUser = user; searchQuery = user.phone; open = false"
                                    class="px-6 py-3 hover:bg-emerald-50 cursor-pointer border-b border-slate-50 last:border-0"
                                >
                                    <div class="text-xs font-black text-slate-900" x-text="user.name"></div>
                                    <div class="text-[10px] text-emerald-600 font-black" x-text="user.phone"></div>
                                </div>
                            </template>
                        </div>
                        
                        <template x-if="searchQuery && filteredUsers.length === 0 && !selectedUser">
                            <div class="mt-2 text-[9px] font-black text-red-400 uppercase tracking-widest ml-1 animate-pulse">
                                No members found matching your search
                            </div>
                        </template>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Credit Amount</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-xl font-black text-slate-900 focus:ring-emerald-400" placeholder="0.00" required>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">BDT</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Execute Protocol
                    </button>
                </form>
            </div>
        </div>

        <!-- Payout Modal -->
        <div x-show="payoutModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-hidden" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                <button @click="payoutModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">payout / <span class="text-blue-600">withdrawal</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Withdraw your agent commission</p>
                </div>
                <form action="{{ route('agent.withdraw.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Withdrawal Amount</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" min="10" max="{{ $stats['wallet_balance'] }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-xl font-black text-slate-900 focus:ring-blue-600" placeholder="0.00" required>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">BDT</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Payment Channel</label>
                        <select name="payment_method" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 focus:ring-blue-600" required>
                            <option value="bKash Personal">bKash Personal</option>
                            <option value="Nagad Personal">Nagad Personal</option>
                            <option value="Bank Account">Bank Wire</option>
                        </select>
                    </div>
                    <textarea name="account_details" rows="2" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 focus:ring-blue-600" placeholder="Target Account Details" required></textarea>
                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Apply for payout
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Persistent Bottom Nav (Mobile - Agent Version) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 px-6 py-4 sm:hidden z-50 flex justify-between items-center shadow-[0_-10px_30px_rgba(0,0,0,0.05)] rounded-t-[2rem]">
        <a href="{{ route('agent.dashboard') }}" class="flex flex-col items-center gap-1">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">💼</div>
            <span class="text-[8px] font-black text-blue-600 uppercase tracking-widest">Terminal</span>
        </a>
        <a href="{{ route('agent.prizes.index') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">🏆</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Prizes</span>
        </a>
        <button @click="depositModal = true" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">💸</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Credit</span>
        </button>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">👤</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Me</span>
        </a>
    </div>
</x-app-layout>