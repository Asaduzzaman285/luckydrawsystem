<x-app-layout>
    <div class="min-h-screen bg-[#f8f9fc] pb-24">
        
        <!-- bKash Pro Header -->
        <div class="bg-gradient-to-r from-[#e2136e] to-[#9d0a4d] pt-8 pb-20 px-4 sm:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center text-white font-black text-xl italic">
                            A
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">system admin</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none">Control Center</div>
                        </div>
                    </div>
                    
                    <!-- System Stats Pill -->
                    <div class="bg-white rounded-full px-4 py-2 flex items-center space-x-3 border border-white/10 shadow-lg">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">System Live</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Service Grid ("Box Box") -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 mb-8 border border-white">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <a href="{{ route('draws.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-pink-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-bkash group-hover:text-white transition-all duration-300 shadow-sm border border-pink-100/50">🗓️</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-bkash leading-tight">manage draws</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-amber-400 transition-all duration-300 shadow-sm border border-amber-100/50">🎁</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-amber-500 leading-tight">products</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300 shadow-sm border border-indigo-100/50">👥</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-indigo-600 leading-tight">users</span>
                    </a>
                    <a href="{{ route('admin.withdrawals.index') }}" class="flex flex-col items-center group text-center relative">
                        @if($stats['pending_withdrawals'] > 0)
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] font-black z-10">{{ $stats['pending_withdrawals'] }}</div>
                        @endif
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-xl sm:text-2xl group-hover:border-bkash border-transparent border-2 transition-all duration-300 shadow-sm">💰</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-900 uppercase tracking-widest mt-2 leading-tight">payouts</span>
                    </a>
                </div>
            </div>

            <!-- Dashboard Stats Hero -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white p-8 rounded-[2rem] border border-white shadow-xl shadow-slate-200/50">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Balance Pool</div>
                    <div class="text-3xl font-black text-slate-900 tracking-tighter italic">৳ {{ number_format($stats['total_balance'], 2) }}</div>
                    <div class="h-1 w-12 bg-bkash mt-4 rounded-full"></div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-white shadow-xl shadow-slate-200/50">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Ongoing Draws</div>
                    <div class="text-3xl font-black text-amber-500 tracking-tighter italic">{{ $stats['active_draws'] }} <span class="text-xs text-slate-400 font-bold not-italic ml-1">Live</span></div>
                    <div class="h-1 w-12 bg-amber-400 mt-4 rounded-full"></div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-white shadow-xl shadow-slate-200/50 bg-gradient-to-br from-white to-pink-50/30">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Pending Requests</div>
                    <div class="text-3xl font-black text-red-500 tracking-tighter italic">{{ $stats['pending_withdrawals'] }} <span class="text-xs text-slate-400 font-bold not-italic ml-1">Awaiting</span></div>
                    <div class="h-1 w-12 bg-red-400 mt-4 rounded-full"></div>
                </div>
            </div>

            <!-- Authorization Queue Table -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200/30 border border-white">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Pending / <span class="text-slate-400 italic font-bold">Withdrawals</span></h3>
                    <a href="{{ route('admin.withdrawals.index') }}" class="text-[9px] font-black text-bkash uppercase tracking-widest hover:underline">View All Requests</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Member</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Amount</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Reference</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Administrative Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pendingWithdrawals as $tx)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-900 border border-slate-200 uppercase italic">
                                                {{ substr($tx->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 text-xs tracking-tight">{{ $tx->user->name }}</div>
                                                <div class="text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ $tx->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">৳ {{ number_format($tx->amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-[10px] text-slate-400 font-mono tracking-widest uppercase truncate max-w-[150px]">{{ $tx->reference_id }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <form action="{{ route('withdrawals.approve', $tx->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-bkash text-white text-[9px] font-black px-5 py-2 rounded-xl uppercase tracking-widest shadow-lg shadow-pink-500/20 hover:scale-105 transition-all outline-none">
                                                Authorize
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-200 italic font-bold uppercase tracking-widest text-[9px]">The Authorization Queue is empty</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>