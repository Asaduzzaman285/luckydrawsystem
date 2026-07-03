<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24">
        <!-- Header Section -->
        <div class="bg-blue-600 rounded-b-[3rem] p-8 pt-12 shadow-2xl relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center text-white backdrop-blur-sm hover:bg-white/20 transition">
                        <span class="text-lg">←</span>
                    </a>
                    <div>
                        <div class="text-[10px] font-black text-white/60 uppercase tracking-widest italic">Admin Console</div>
                        <h1 class="text-2xl font-black text-white tracking-tight italic">All Transaction Reports</h1>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-2 pb-2">
                    <a href="{{ route('admin.reports') }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest italic whitespace-nowrap {{ !request('type') && !request('agent_id') ? 'bg-white text-blue-600' : 'bg-white/10 text-white hover:bg-white/20' }}">All</a>
                    <a href="{{ route('admin.reports', ['type' => 'deposit']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest italic whitespace-nowrap {{ request('type') == 'deposit' ? 'bg-white text-blue-600' : 'bg-white/10 text-white hover:bg-white/20' }}">Deposits</a>
                    <a href="{{ route('admin.reports', ['type' => 'withdrawal']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest italic whitespace-nowrap {{ request('type') == 'withdrawal' ? 'bg-white text-blue-600' : 'bg-white/10 text-white hover:bg-white/20' }}">Withdrawals</a>
                </div>

                <!-- Agent Filter -->
                <form method="GET" action="{{ route('admin.reports') }}" class="mt-3">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <select name="agent_id" onchange="this.form.submit()" class="bg-white/10 border-white/20 text-white text-xs font-bold rounded-xl px-4 py-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm">
                        <option value="" class="text-slate-900">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }} class="text-slate-900">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="px-4 md:px-8 max-w-7xl mx-auto">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white">
                <div class="px-4 md:px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">All Transactions / <span class="text-blue-600 font-bold italic">{{ $transactions->total() }} Records</span></h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Transaction ID</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Customer</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Processed By</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Type</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Date</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($transactions as $txn)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-[9px] font-black text-slate-400 tracking-widest uppercase italic">{{ $txn->reference_id ?? 'TXN-'.$txn->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-black text-slate-900 tracking-tight italic">{{ $txn->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold italic">{{ $txn->user->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($txn->processedBy)
                                            <div class="text-xs font-black text-blue-600 tracking-tight italic">{{ $txn->processedBy->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold italic">Agent</div>
                                        @else
                                            <div class="text-[9px] text-slate-300 font-bold italic uppercase tracking-widest">System</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($txn->type == 'deposit')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-widest italic border border-emerald-100">Deposit</span>
                                        @elseif($txn->type == 'withdrawal')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-amber-50 text-amber-600 uppercase tracking-widest italic border border-amber-100">Withdrawal</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-slate-50 text-slate-600 uppercase tracking-widest italic border border-slate-100">{{ $txn->type }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $txn->created_at->format('M d, Y h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-[11px] font-black {{ $txn->type == 'deposit' ? 'text-emerald-600' : 'text-slate-900' }} italic">
                                            {{ $txn->type == 'deposit' ? '+' : '-' }} ৳ {{ number_format($txn->amount, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
