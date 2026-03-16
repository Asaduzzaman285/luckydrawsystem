<x-app-layout>
    <div class="py-12">
        <div class="mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">win / <span class="text-blue-600">history</span></h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2">Official archive of all processed draws and winners</p>
                </div>
                <div class="bg-white px-6 py-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-300 uppercase tracking-widest">Last Update</span>
                        <span class="text-xs font-bold text-slate-600 italic">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($draws as $draw)
                <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-xl shadow-blue-900/5 flex flex-col group hover:border-blue-200 transition-all duration-500">
                    <div class="p-8 border-b border-slate-50 relative overflow-hidden">
                        <div class="absolute -right-8 -top-8 w-32 h-32 bg-slate-50 rounded-full opacity-50 group-hover:bg-blue-50 transition-colors"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="inline-block px-3 py-1 bg-slate-100 text-[10px] font-black text-slate-500 rounded-lg uppercase tracking-widest mb-3 italic">Draw Protocol #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none italic">{{ $draw->title }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</span>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[9px] font-black rounded-full uppercase tracking-widest italic">Finalized</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-50">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Sales</span>
                                    <span class="text-sm font-black text-slate-900 italic">৳{{ number_format($draw->total_sales, 0) }}</span>
                                </div>
                                <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/30">
                                    <span class="block text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Prize Pool</span>
                                    <span class="text-sm font-black text-blue-600 italic">৳{{ number_format($draw->prize_pool_total, 0) }}</span>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-50">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date</span>
                                    <span class="text-sm font-black text-slate-900 italic">{{ $draw->draw_time->format('M d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50/50 flex-grow">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Highlighted Winners</h4>
                            <div class="h-px bg-slate-200 flex-grow mx-4"></div>
                        </div>

                        <div class="space-y-3">
                            @php
                                $sortedWinners = $draw->winners->sortBy('prize_tier_id');
                                $tiers = [
                                    1 => '1st / Grand Prize',
                                    2 => '2nd Prize',
                                    3 => '3rd Prize',
                                    4 => '4th Prize',
                                    5 => '5th Prize',
                                ];
                            @endphp

                            @foreach($sortedWinners->take(5) as $winner)
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 flex items-center justify-between shadow-sm hover:shadow transition group/item">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-lg border border-slate-100 group-hover/item:border-blue-200 transition-colors">
                                            @if($winner->prize_tier_id == 1) 🥇 @elseif($winner->prize_tier_id == 2) 🥈 @elseif($winner->prize_tier_id == 3) 🥉 @else 🎖️ @endif
                                        </div>
                                        <div>
                                            <span class="block text-[9px] font-black text-slate-300 uppercase tracking-widest mb-0.5">{{ $tiers[$winner->prize_tier_id] ?? 'Winner' }}</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-black text-slate-900 italic tracking-tight">{{ substr($winner->user->name, 0, 3) }}***{{ substr($winner->user->name, -1) }}</span>
                                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100 italic">{{ $winner->ticket_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-black text-emerald-600 italic tracking-tighter">+ ৳{{ number_format($winner->prize_amount, 0) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @if($sortedWinners->count() > 5)
                                <div class="text-center pt-2">
                                    <p class="text-[10px] font-bold text-slate-400 italic italic tracking-widest uppercase">... and {{ $sortedWinners->count() - 5 }} other winners rewarded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200 shadow-inner">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6 opacity-40">📭</div>
                    <h3 class="text-xl font-black text-slate-400 italic">No historical records found.</h3>
                    <p class="text-slate-300 text-xs mt-2 uppercase font-bold tracking-widest">Archives are updated as soon as draws conclude</p>
                </div>
            @endforelse
        </div>

        @if($draws->hasPages())
            <div class="mt-12">
                {{ $draws->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
