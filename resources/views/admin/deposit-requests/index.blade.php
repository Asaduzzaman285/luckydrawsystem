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
                        <h1 class="text-2xl font-black text-white tracking-tight italic">Agent Deposit Requests</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 md:px-8 max-w-7xl mx-auto">
            
            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-2xl mb-8">
                <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest italic mb-2">Agent Wallet Top-Up Requests</h3>
                <p class="text-[11px] text-blue-800/80 font-medium leading-relaxed">
                    Agents send money to your bKash/Nagad and then submit a request here. Verify the Transaction ID in your account before approving. Approval will credit the agent's wallet.
                </p>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white">
                <div class="px-4 md:px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Agent Requests / <span class="text-blue-600 font-bold italic">{{ $requests->count() }} Total</span></h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[800px]">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Agent</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Method</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">TrxID</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Amount</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Date</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Status</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($requests as $request)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-black text-slate-900 tracking-tight italic">{{ $request->user->name }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold italic">{{ $request->user->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[10px] font-black text-slate-600 uppercase tracking-widest italic">{{ $request->payment_method }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] font-black text-blue-600 uppercase tracking-widest italic bg-blue-50 px-2 py-1 rounded inline-block border border-blue-100">{{ $request->transaction_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] font-black text-slate-900 italic">৳ {{ number_format($request->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $request->created_at->format('M d, h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($request->status == 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-amber-50 text-amber-600 uppercase tracking-widest italic border border-amber-100">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-widest italic border border-emerald-100">Approved</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-50 text-red-600 uppercase tracking-widest italic border border-red-100">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($request->status == 'pending')
                                            <div class="flex justify-end space-x-2" x-data="{ rejectModal: false }">
                                                <form action="{{ route('admin.deposit-requests.approve', $request->id) }}" method="POST" onsubmit="return confirm('Approve this request? The amount will be added to the agent\'s wallet.');">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-600 text-white text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-emerald-700 transition shadow-md shadow-emerald-900/20 italic">Approve</button>
                                                </form>
                                                
                                                <button @click="rejectModal = true" type="button" class="bg-red-50 text-red-600 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-red-100 transition border border-red-100 italic">Reject</button>

                                                <!-- Reject Modal -->
                                                <div x-show="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
                                                    <div @click.away="rejectModal = false" class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl">
                                                        <h4 class="text-sm font-black text-slate-900 uppercase italic mb-4">Reject Deposit Request</h4>
                                                        <form action="{{ route('admin.deposit-requests.reject', $request->id) }}" method="POST">
                                                            @csrf
                                                            <div class="space-y-4 text-left">
                                                                <div>
                                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block mb-2">Reason for rejection</label>
                                                                    <input type="text" name="rejection_reason" required placeholder="e.g. Invalid TrxID" class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 text-xs font-black text-slate-900">
                                                                </div>
                                                                <div class="flex space-x-3">
                                                                    <button type="button" @click="rejectModal = false" class="flex-1 bg-slate-100 text-slate-600 text-[10px] font-black py-3 rounded-xl uppercase italic hover:bg-slate-200 transition">Cancel</button>
                                                                    <button type="submit" class="flex-1 bg-red-600 text-white text-[10px] font-black py-3 rounded-xl uppercase shadow-md shadow-red-900/20 italic hover:bg-red-700 transition">Confirm Reject</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($request->status == 'rejected')
                                            <div class="text-[9px] text-red-400 font-bold italic">{{ $request->rejection_reason }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">
                                        No deposit requests from agents.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
