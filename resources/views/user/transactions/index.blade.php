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
                        <div class="text-[10px] font-black text-white/60 uppercase tracking-widest italic">My Account</div>
                        <h1 class="text-2xl font-black text-white tracking-tight italic">Transaction History</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 md:px-8 max-w-7xl mx-auto">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white">
                <div class="px-4 md:px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Transactions / <span class="text-blue-600 font-bold italic">{{ $transactions->total() }} Records</span></h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[600px]">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Transaction ID</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Type</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Date</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Remarks</th>
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
                                        @if($txn->type == 'deposit' || $txn->type == 'credit' || $txn->type == 'prize')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-widest italic border border-emerald-100">{{ $txn->type }}</span>
                                        @elseif($txn->type == 'withdrawal' || $txn->type == 'debit' || $txn->type == 'purchase')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-amber-50 text-amber-600 uppercase tracking-widest italic border border-amber-100">{{ $txn->type }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-slate-50 text-slate-600 uppercase tracking-widest italic border border-slate-100">{{ $txn->type }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $txn->created_at->format('M d, Y h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-black text-slate-700 italic">{{ $txn->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-[11px] font-black {{ in_array($txn->type, ['deposit', 'credit', 'prize']) ? 'text-emerald-600' : 'text-slate-900' }} italic">
                                            {{ in_array($txn->type, ['deposit', 'credit', 'prize']) ? '+' : '-' }} ৳ {{ number_format($txn->amount, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
