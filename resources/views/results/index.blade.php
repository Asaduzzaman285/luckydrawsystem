<x-app-layout>
<div class="py-8 px-4 sm:px-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="font-black text-3xl tracking-tight italic" style="font-family:'Syne',sans-serif">
                win / <span class="text-blue-600">history</span>
            </h1>
            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-1">
                Official archive of all processed draws and winners
            </p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-2.5 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest">Last Update</span>
                <span class="text-xs font-bold text-slate-800">{{ now()->format('F d, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($draws as $draw)

        @php
            $sortedWinners = $draw->winners->sortBy('prize_tier_id');
            $tiers = [1=>'1st / Grand Prize',2=>'2nd Prize',3=>'3rd Prize',4=>'4th Prize',5=>'5th Prize'];
        @endphp

        <div class="bg-white rounded-[1.1rem] border border-slate-100 overflow-hidden flex flex-col shadow-sm hover:border-slate-200 hover:-translate-y-px transition-all duration-200">

            {{-- Dark header --}}
            <div class="relative overflow-hidden px-5 py-4" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
                <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full pointer-events-none" style="background:rgba(37,99,235,.14)"></div>

                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <span class="inline-block text-[8px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 border border-white/10 rounded px-2 py-0.5 mb-1.5">
                            Draw Protocol #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                        <h3 class="text-[16px] font-black text-white tracking-tight italic leading-none" style="font-family:'Syne',sans-serif">
                            {{ $draw->title }}
                        </h3>
                    </div>
                    <div class="flex flex-col items-end gap-2 text-right">
                        <div class="flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full px-2.5 py-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span class="text-[7px] font-bold text-emerald-400 uppercase tracking-widest">Finalized</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 italic" style="font-family:'Syne',sans-serif">{{ $draw->draw_time->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Winners body --}}
            <div class="px-4 py-4 flex-1">
                @php
                    $winnersByTier = $draw->winners->groupBy('prize_tier_id');
                @endphp

                {{-- Tiers 1-3: Individual Records --}}
                @if($winnersByTier->has(1) || $winnersByTier->has(2) || $winnersByTier->has(3))
                <div class="space-y-1.5 mb-4">
                    @foreach([1, 2, 3] as $tid)
                        @if($winnersByTier->has($tid))
                            @foreach($winnersByTier->get($tid) as $winner)
                            <div class="flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2">
                                <div class="w-7 h-7 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                                    @if($tid==1)🥇@elseif($tid==2)🥈@elseif($tid==3)🥉@endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[7px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ $tiers[$tid] }}</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-bold text-slate-800 italic" style="font-family:'Syne',sans-serif">{{ substr($winner->user->name,0,3) }}***{{ substr($winner->user->name,-1) }}</span>
                                        <span class="text-[9px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 rounded px-1.5 py-0.5 font-mono tracking-wide">{{ $winner->ticket_number }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[12px] font-black text-emerald-600 italic" style="font-family:'Syne',sans-serif">+৳{{ number_format($winner->prize_amount, 0) }}</span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
                @endif

                {{-- Tiers 4-5: Compact Grids --}}
                @if($winnersByTier->has(4) || $winnersByTier->has(5))
                <div class="space-y-3">
                    @foreach([4, 5] as $tid)
                        @if($winnersByTier->has($tid))
                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $tiers[$tid] }}</span>
                                <div class="h-px flex-1 bg-slate-200/50"></div>
                                <span class="text-[8px] font-black text-emerald-600 italic">৳{{ number_format($winnersByTier->get($tid)->first()->prize_amount, 0) }} each</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($winnersByTier->get($tid) as $winner)
                                    <div class="bg-white border border-slate-100 rounded-md px-1.5 py-0.5 shadow-sm">
                                        <span class="text-[8px] font-bold text-slate-500 font-mono tracking-tighter">{{ $winner->ticket_number }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200">
            <div class="text-3xl mb-4 opacity-30">📭</div>
            <h3 class="text-lg font-black text-slate-400 italic" style="font-family:'Syne',sans-serif">No historical records found.</h3>
            <p class="text-slate-300 text-[9px] mt-2 uppercase font-bold tracking-widest">Archives updated as soon as draws conclude</p>
        </div>
        @endforelse
    </div>

    @if($draws->hasPages())
    <div class="mt-8">{{ $draws->links() }}</div>
    @endif
</div>
</x-app-layout>
</x-app-layout>