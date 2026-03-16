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
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-2.5 flex items-center gap-3 self-start sm:self-auto shadow-sm">
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2.5"/>
                    <polyline points="12 6 12 12 16 14" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">Last Update</span>
                <span class="text-xs font-bold text-slate-800" style="font-family:'Syne',sans-serif">{{ now()->format('F d, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Draw Cards Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($draws as $draw)
        <div class="bg-white rounded-[1.25rem] border border-slate-100 overflow-hidden flex flex-col shadow-sm hover:border-slate-200 hover:-translate-y-px transition-all duration-200">

            {{-- Dark Header --}}
            <div class="relative overflow-hidden px-5 py-4" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
                <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full" style="background:rgba(37,99,235,.14)"></div>
                <div class="absolute right-5 -bottom-2 w-10 h-10 rounded-full" style="background:rgba(37,99,235,.07)"></div>

                {{-- Title row --}}
                <div class="flex justify-between items-start mb-3 relative z-10">
                    <div>
                        <span class="inline-block text-[9px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 border border-white/10 rounded-md px-2 py-0.5 mb-1.5">
                            Draw Protocol #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                        <h3 class="text-[17px] font-black text-white tracking-tight leading-none italic" style="font-family:'Syne',sans-serif">
                            {{ $draw->title }}
                        </h3>
                    </div>
                    <div class="flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full px-2.5 py-1 flex-shrink-0 ml-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
                        <span class="text-[8px] font-bold text-emerald-400 uppercase tracking-widest">Finalized</span>
                    </div>
                </div>

                {{-- Stats row --}}
                <div class="grid grid-cols-3 gap-2 relative z-10">
                    <div class="bg-white/5 border border-white/8 rounded-lg px-2.5 py-2">
                        <span class="block text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Total Sales</span>
                        <span class="text-[13px] font-black text-slate-200 italic" style="font-family:'Syne',sans-serif">৳{{ number_format($draw->total_sales, 0) }}</span>
                    </div>
                    <div class="bg-white/5 border border-white/8 rounded-lg px-2.5 py-2">
                        <span class="block text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Prize Pool</span>
                        <span class="text-[13px] font-black text-blue-400 italic" style="font-family:'Syne',sans-serif">৳{{ number_format($draw->prize_pool_total, 0) }}</span>
                    </div>
                    <div class="bg-white/5 border border-white/8 rounded-lg px-2.5 py-2">
                        <span class="block text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Draw Date</span>
                        <span class="text-[13px] font-black text-slate-200 italic" style="font-family:'Syne',sans-serif">{{ $draw->draw_time->format('M d') }}</span>
                    </div>
                </div>
            </div>

            {{-- Winners Body --}}
            <div class="px-4 py-4 flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Highlighted Winners</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>

                @php
                    $sortedWinners = $draw->winners->sortBy('prize_tier_id');
                    $tiers = [1=>'1st / Grand Prize',2=>'2nd Prize',3=>'3rd Prize',4=>'4th Prize',5=>'5th Prize'];
                @endphp

                <div class="space-y-1.5">
                    @foreach($sortedWinners->take(5) as $winner)
                    <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 hover:border-slate-200 transition-colors">
                        <div class="w-8 h-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-sm flex-shrink-0">
                            @if($winner->prize_tier_id == 1) 🥇
                            @elseif($winner->prize_tier_id == 2) 🥈
                            @elseif($winner->prize_tier_id == 3) 🥉
                            @else 🎖️ @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                                {{ $tiers[$winner->prize_tier_id] ?? 'Winner' }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-bold text-slate-800 italic" style="font-family:'Syne',sans-serif">
                                    {{ substr($winner->user->name, 0, 3) }}***{{ substr($winner->user->name, -1) }}
                                </span>
                                <span class="text-[9px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 rounded px-1.5 py-0.5 font-mono tracking-wide">
                                    {{ $winner->ticket_number }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[13px] font-black text-emerald-600 flex-shrink-0 italic" style="font-family:'Syne',sans-serif">
                            +৳{{ number_format($winner->prize_amount, 0) }}
                        </span>
                    </div>
                    @endforeach
                </div>

                @if($sortedWinners->count() > 5)
                <p class="text-center text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-3">
                    ... and {{ $sortedWinners->count() - 5 }} other winners rewarded
                </p>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200">
            <div class="text-3xl mb-4 opacity-30">📭</div>
            <h3 class="text-lg font-black text-slate-400 italic" style="font-family:'Syne',sans-serif">No historical records found.</h3>
            <p class="text-slate-300 text-[9px] mt-2 uppercase font-bold tracking-widest">Archives are updated as soon as draws conclude</p>
        </div>
        @endforelse
    </div>

    @if($draws->hasPages())
    <div class="mt-8">{{ $draws->links() }}</div>
    @endif
</div>
</x-app-layout>