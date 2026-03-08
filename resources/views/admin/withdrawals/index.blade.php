<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-8">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">admin</a>
                    <span>/</span>
                    <span class="text-blue-600">withdrawals</span>
                </nav>
                <h1 class="text-pro-title">capital outflow registry</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Authorize and manage agent liquidity requests</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-600 font-bold flex items-center space-x-3 animate-fade-in text-xs uppercase italic">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-600 font-bold flex items-center space-x-3 animate-fade-in text-xs uppercase italic">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="stats-card">
                    <span class="stats-label">Pending Requests</span>
                    <div class="stats-value text-blue-600 italic">{{ $requests->where('status', 'pending')->count() }}</div>
                    <span class="stats-subtext">Awaiting authorization</span>
                </div>
                <div class="stats-card">
                    <span class="stats-label">Authorized Today</span>
                    <div class="stats-value text-emerald-500 italic">৳{{ number_format($requests->where('status', 'approved')->where('updated_at', '>=', now()->startOfDay())->sum('amount'), 2) }}</div>
                    <span class="stats-subtext">Approved since midnight</span>
                </div>
                <div class="stats-card">
                    <span class="stats-label">Global Withdrawal Volume</span>
                    <div class="stats-value text-slate-900 italic">৳{{ number_format($requests->where('status', 'approved')->sum('amount') / 1000, 1) }}k</div>
                    <span class="stats-subtext">Total liquidity processed</span>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Personnel & Node</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Valuation</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Protocol & Terminal</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Timestamp</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Administrative Decision</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($requests as $req)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-[10px] font-black text-white shadow-lg">
                                                {{ strtoupper(substr($req->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 tracking-tight italic">{{ $req->user->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $req->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter italic">৳{{ number_format($req->amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ $req->payment_method }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1 truncate max-w-[150px]">{{ $req->account_details }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="badge-dot {{ $req->status === 'pending' ? 'badge-dot-live' : ($req->status === 'approved' ? 'badge-dot-live' : 'badge-dot-ended') }}">
                                            <span class="dot {{ $req->status === 'pending' ? 'dot-live !bg-blue-400' : ($req->status === 'approved' ? 'dot-live' : 'dot-ended') }}"></span>
                                            {{ strtoupper($req->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $req->created_at->format('M d, Y') }}</div>
                                        <div class="text-[9px] text-slate-400 font-black mt-0.5">{{ $req->created_at->format('H:i') }} UTC</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($req->status === 'pending')
                                            <div class="flex items-center justify-end space-x-3" x-data="{ showReject: false }">
                                                <form action="{{ route('admin.withdrawals.approve', $req->id) }}" method="POST" onsubmit="return confirm('Authorize Capital Outflow?');">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 text-white text-[9px] font-black px-5 py-2 rounded-xl uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all outline-none">
                                                        Authorize
                                                    </button>
                                                </form>
                                                <button @click="showReject = true" class="btn-pro-danger !rounded-xl">
                                                    Terminate
                                                </button>

                                                <!-- Inline Rejection UI -->
                                                <template x-if="showReject">
                                                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                                                        <div class="bg-white rounded-[2rem] p-10 shadow-2xl max-w-md w-full border border-white animate-fade-in-up">
                                                            <h3 class="text-xl font-black text-slate-950 tracking-tighter uppercase italic mb-2 text-left">Protocol Rejection</h3>
                                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6 text-left">Provide termination justification</p>
                                                            
                                                            <form action="{{ route('admin.withdrawals.reject', $req->id) }}" method="POST" class="space-y-4">
                                                                @csrf
                                                                <textarea name="admin_notes" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-xs" rows="3" placeholder="Rejection reasoning..."></textarea>
                                                                <div class="flex items-center space-x-4">
                                                                    <button type="submit" class="bg-red-600 text-white text-[9px] font-black py-4 flex-1 rounded-xl uppercase tracking-widest shadow-lg shadow-red-500/20 hover:scale-105 transition-all">Commit Rejection</button>
                                                                    <button type="button" @click="showReject = false" class="text-[10px] font-black uppercase text-slate-400 hover:text-slate-900 transition">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] italic">De-active Protocol</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[10px]">No Outflow Requests Logged</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($requests->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
