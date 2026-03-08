<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-8">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('agent.dashboard') }}" class="hover:text-blue-600 transition italic">agent</a>
                    <span>/</span>
                    <span class="text-blue-600 italic">prize protocol</span>
                </nav>
                <h1 class="text-pro-title">winning asset registry</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Manage and authorize grand prize distributions for your sector</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success') || session('error'))
                <div class="mb-8 p-4 {{ session('success') ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600' : 'bg-red-500/10 border-red-500/20 text-red-600' }} border rounded-[1.5rem] font-bold flex items-center space-x-3 animate-fade-in shadow-sm italic text-xs uppercase tracking-tight">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ session('success') ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path></svg>
                    <span>{{ session('success') ?? session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tighter italic">Pending Distributions</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Physical and digital assets awaiting handover confirmation</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[9px] font-black rounded-full uppercase tracking-widest border border-blue-100 italic">Tier 1-3 Focus</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic">Asset Winner</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic text-center">Protocol Tier</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic">Valuation</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic">Operational Ref</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 italic text-right">Action Protocol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($prizes as $prize)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center text-[10px] font-black text-blue-600 border border-blue-100 shadow-sm italic">
                                                {{ strtoupper(substr($prize->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 tracking-tight text-sm italic">{{ $prize->user->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 italic">{{ $prize->user->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="px-3 py-1 bg-blue-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest italic shadow-lg shadow-blue-500/20">
                                            Tier {{ $prize->prize_tier_id }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter italic">৳ {{ number_format($prize->prize_amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-[10px] font-black text-slate-400 font-mono tracking-widest uppercase italic">{{ $prize->ticket_number }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="badge-dot {{ $prize->status === 'completed' ? 'badge-dot-live' : 'badge-dot-ended' }}">
                                            <span class="dot {{ $prize->status === 'completed' ? 'dot-live' : 'dot-ended' }}"></span>
                                            {{ strtoupper($prize->status === 'completed' ? 'Distributed' : 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($prize->status !== 'completed')
                                            <div x-data="{ showModal: false }">
                                                <button @click="showModal = true" class="btn-pro-primary !py-2.5 !px-6 !rounded-xl !text-[10px] !tracking-widest uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition italic">
                                                    Authorize Handover
                                                </button>

                                                <!-- Pro Modal -->
                                                <template x-if="showModal">
                                                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                                                        <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl max-w-md w-full border border-white animate-fade-in-up text-left">
                                                            <div class="mb-8">
                                                                <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic mb-2">Protocol / <span class="text-blue-600">Confirmation</span></h3>
                                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Verify physical asset handover to holder</p>
                                                            </div>

                                                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 mb-8 shadow-inner">
                                                                <div class="flex justify-between items-center mb-4">
                                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Recipient</span>
                                                                    <span class="text-xs font-black text-slate-900 tracking-tight italic">{{ $prize->user->name }}</span>
                                                                </div>
                                                                <div class="flex justify-between items-center">
                                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Valuation</span>
                                                                    <span class="text-xs font-black text-blue-600 tracking-tight italic">৳ {{ number_format($prize->prize_amount, 2) }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <form action="{{ route('agent.prizes.distribute', $prize->id) }}" method="POST" class="space-y-6">
                                                                @csrf
                                                                <div>
                                                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Protocol Log Notes (Optional)</label>
                                                                    <textarea name="notes" class="w-full bg-slate-50 border-slate-200 rounded-[1.5rem] py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 transition text-xs shadow-inner" rows="3" placeholder="Reference ceremony or ID details..."></textarea>
                                                                </div>
                                                                <div class="flex items-center space-x-4">
                                                                    <button type="submit" class="btn-pro-primary flex-1 !rounded-2xl !py-4 uppercase tracking-widest text-[10px] shadow-xl shadow-blue-500/20 italic">Commit Distribution</button>
                                                                    <button type="button" @click="showModal = false" class="text-[10px] font-black uppercase text-slate-400 hover:text-slate-900 transition underline underline-offset-4 decoration-blue-600 italic">Abort</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-end">
                                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Node Verified</div>
                                                <div class="text-[8px] text-slate-300 font-bold uppercase mt-1 italic">
                                                    {{ \Carbon\Carbon::parse($prize->metadata['distributed_at'] ?? now())->format('d M, Y | H:i') }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center">
                                        <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No Assets Pending Handover</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($prizes->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50">
                        {{ $prizes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
