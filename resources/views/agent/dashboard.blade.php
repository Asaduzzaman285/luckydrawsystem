<x-app-layout>
    <div class="min-h-screen bg-gray-900 pb-24"
        x-data="{ 
            registerModal: false, 
            depositModal: false, 
            payoutModal: false 
        }"
        x-on:open-modal.window="
            if ($event.detail === 'register-member') registerModal = true;
            if ($event.detail === 'deposit-fund') depositModal = true;
        ">
        
        <!-- bKash Pro Header -->
        <div class="bg-gradient-to-r from-[#e2136e] to-[#9d0a4d] pt-8 pb-20 px-4 sm:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center text-white font-black text-xl italic">
                            A
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">agent terminal</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Balance Toggle Pill (Agent Earnings) -->
                    <div x-data="{ show: false }" @click="show = !show" class="balance-pill group scale-90 sm:scale-100 origin-right">
                        <div class="w-7 h-7 bg-bkash rounded-full flex items-center justify-center text-white text-[10px] font-black italic">৳</div>
                        <div class="relative flex-1 overflow-hidden h-5">
                            <div x-show="!show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-[10px] font-black text-bkash uppercase tracking-widest pt-0.5">tap for earnings</div>
                            <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-sm font-black text-bkash tracking-tighter pt-0">৳ {{ number_format($stats['wallet_balance'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Service Grid ("Box Box") -->
            <div class="bg-maroon rounded-3xl p-6 shadow-2xl mb-8 border border-white/10 text-center">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <button @click="registerModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-pink-600 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">👤</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 group-hover:text-white">add user</span>
                    </button>
                    <button @click="depositModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-emerald-600 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">💸</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 group-hover:text-white">add money</span>
                    </button>
                    <button @click="payoutModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-indigo-600 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">🏦</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 group-hover:text-white">withdraw</span>
                    </button>
                    <a href="{{ route('agent.prizes.index') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-amber-500 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">🏆</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 group-hover:text-white">prizes</span>
                    </a>
                </div>
            </div>

            <!-- Agent Growth Hero -->
            <div class="mb-10">
                <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl relative p-8 sm:p-12 text-white border border-white/10">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div>
                            <h2 class="text-3xl font-black tracking-tighter italic lowercase mb-4">Operations / <span class="text-bkash">Summary</span></h2>
                            <div class="flex flex-wrap gap-6 mt-6">
                                <div>
                                    <div class="text-[9px] font-black text-white/60 uppercase tracking-widest mb-1">Total Processed</div>
                                    <div class="text-2xl font-black tracking-tighter">৳ {{ number_format($stats['total_deposits'], 2) }}</div>
                                </div>
                                <div class="px-6 border-l border-white/10">
                                    <div class="text-[9px] font-black text-white/60 uppercase tracking-widest mb-1">Active Users</div>
                                    <div class="text-2xl font-black tracking-tighter text-blue-400">{{ $stats['users_created'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-xl p-8 rounded-3xl border border-white/10 text-center min-w-[200px]">
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-2">My Commission</div>
                            <div class="text-4xl font-black text-bkash tracking-tighter leading-none mb-4">৳ {{ number_format($stats['wallet_balance'], 2) }}</div>
                            <button @click="payoutModal = true" class="w-full bg-white text-slate-900 text-[10px] font-black py-3 rounded-xl uppercase tracking-widest hover:bg-bkash hover:text-white transition-all">Request payout</button>
                        </div>
                    </div>
                    <!-- Background blobs -->
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-bkash/10 rounded-full blur-[100px]"></div>
                    <div class="absolute -left-20 -top-20 w-64 h-64 bg-blue-500/5 rounded-full blur-[100px]"></div>
                </div>
            </div>

            <!-- Payout Request History -->
            <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 text-white">
                <div class="px-8 py-5 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em] italic">Payout / <span class="text-white/60 italic font-bold">Log</span></h3>
                    <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Last 10 Records</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Method</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Amount</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Identity Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10 text-right">Age</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($withdrawalRequests as $request)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-8 py-4">
                                        <div class="font-black text-white text-xs tracking-tight">{{ $request->payment_method }}</div>
                                        <div class="text-[9px] text-white/40 font-bold mt-1 italic">REF: {{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-black text-white tracking-tighter">৳ {{ number_format($request->amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if($request->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-amber-500/20 text-amber-400 uppercase tracking-widest">Awaiting Admin</span>
                                        @elseif($request->status === 'approved' || $request->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-500/20 text-emerald-400 uppercase tracking-widest">Authorized</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-500/20 text-red-400 uppercase tracking-widest">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="text-[9px] font-black text-white/40 uppercase tracking-[0.1em]">{{ $request->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-white/20 italic font-bold uppercase tracking-widest text-[9px]">Zero withdrawal Activity Records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div x-show="registerModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white/20">
                <button @click="registerModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">onboard / <span class="text-bkash">user</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic tracking-widest">Create a new member account</p>
                </div>
                <form action="{{ route('agent.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-bkash transition text-sm" placeholder="Full Name" required>
                    <input type="email" name="email" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-bkash transition text-sm" placeholder="Email Address" required>
                    <input type="password" name="password" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-bkash transition text-sm" placeholder="Initial Password" required>
                    <button type="submit" class="w-full bg-bkash text-white font-black py-4 rounded-2xl shadow-xl shadow-pink-500/20 hover:bg-slate-900 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Finalize Onboarding
                    </button>
                </form>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div x-show="depositModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white/20">
                <button @click="depositModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">credit / <span class="text-emerald-500">transfer</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic tracking-widest">Move balance to a member account</p>
                </div>
                <form action="{{ route('agent.deposit.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Target Account</label>
                        <select name="user_id" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-emerald-400 text-sm" required>
                            <option value="">Choose member...</option>
                            @foreach(\App\Models\User::role('user')->where('created_by', auth()->id())->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Credit Amount</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 text-xl font-black text-slate-900 focus:ring-emerald-400" placeholder="0.00" required>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-slate-300">BDT</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Execute Protocol
                    </button>
                </form>
            </div>
        </div>

        <!-- Payout Modal -->
        <div x-show="payoutModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative border border-white/20">
                <button @click="payoutModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition font-black">✕</button>
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic">payout / <span class="text-bkash">withdrawal</span></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic tracking-widest">Withdraw your agent commission</p>
                </div>
                <form action="{{ route('agent.withdraw.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Withdrawal Amount</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" min="10" max="{{ $stats['wallet_balance'] }}" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 text-xl font-black text-slate-900 focus:ring-bkash" placeholder="0.00" required>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-slate-300">BDT</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Payment Channel</label>
                        <select name="payment_method" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 focus:ring-bkash" required>
                            <option value="bKash Personal">bKash Personal</option>
                            <option value="Nagad Personal">Nagad Personal</option>
                            <option value="Bank Account">Bank Wire</option>
                        </select>
                    </div>
                    <textarea name="account_details" rows="2" class="w-full bg-slate-50 border-transparent rounded-2xl py-4 px-6 text-sm font-bold text-slate-900 focus:ring-bkash" placeholder="Target Account Details" required></textarea>
                    <button type="submit" class="w-full bg-bkash text-white font-black py-4 rounded-2xl shadow-xl shadow-pink-500/20 hover:bg-slate-900 transition duration-300 uppercase tracking-[0.2em] text-[10px]">
                        Apply for payout
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Persistent Bottom Nav (Mobile - Agent Version) -->
    <div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-white/5 px-6 py-4 sm:hidden z-50 flex justify-between items-center shadow-[0_-10px_30px_rgba(0,0,0,0.2)] rounded-t-[2rem]">
        <a href="{{ route('agent.dashboard') }}" class="flex flex-col items-center gap-1">
            <div class="w-8 h-8 rounded-full bg-bkash flex items-center justify-center text-white shadow-lg shadow-pink-500/20">💼</div>
            <span class="text-[8px] font-black text-bkash uppercase tracking-widest">Terminal</span>
        </a>
        <a href="{{ route('agent.prizes.index') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">🏆</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Prizes</span>
        </a>
        <button @click="depositModal = true" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">💸</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Credit</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">👤</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Me</span>
        </a>
    </div>
</x-app-layout>