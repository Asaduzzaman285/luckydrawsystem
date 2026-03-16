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

                <div class="flex justify-between items-start mb-3 relative z-10">
                    <div>
                        <span class="inline-block text-[8px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 border border-white/10 rounded px-2 py-0.5 mb-1.5">
                            Draw Protocol #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                        <h3 class="text-[16px] font-black text-white tracking-tight italic leading-none" style="font-family:'Syne',sans-serif">
                            {{ $draw->title }}
                        </h3>
                    </div>
                    <div class="flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full px-2.5 py-1 ml-3 flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[7px] font-bold text-emerald-400 uppercase tracking-widest">Finalized</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-1.5 relative z-10">
                    <div class="bg-white/5 border border-white/[.07] rounded-lg px-2.5 py-2">
                        <span class="block text-[7px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Total Sales</span>
                        <span class="text-[12px] font-black text-slate-200 italic" style="font-family:'Syne',sans-serif">৳{{ number_format($draw->total_sales, 0) }}</span>
                    </div>
                    <div class="bg-white/5 border border-white/[.07] rounded-lg px-2.5 py-2">
                        <span class="block text-[7px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Prize Pool</span>
                        <span class="text-[12px] font-black text-blue-400 italic" style="font-family:'Syne',sans-serif">৳{{ number_format($draw->prize_pool_total, 0) }}</span>
                    </div>
                    <div class="bg-white/5 border border-white/[.07] rounded-lg px-2.5 py-2">
                        <span class="block text-[7px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Draw Date</span>
                        <span class="text-[12px] font-black text-slate-200 italic" style="font-family:'Syne',sans-serif">{{ $draw->draw_time->format('M d') }}</span>
                    </div>
                </div>
            </div>

            {{-- Winners body --}}
            <div class="px-4 py-4 flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Highlighted Winners</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>
                <div class="space-y-1.5">
                    @foreach($sortedWinners->take(5) as $winner)
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 hover:border-slate-200 transition-colors">
                        <div class="w-7 h-7 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-xs flex-shrink-0">
                            @if($winner->prize_tier_id==1)🥇@elseif($winner->prize_tier_id==2)🥈@elseif($winner->prize_tier_id==3)🥉@else🎖️@endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-[7px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ $tiers[$winner->prize_tier_id] ?? 'Winner' }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-bold text-slate-800 italic" style="font-family:'Syne',sans-serif">{{ substr($winner->user->name,0,3) }}***{{ substr($winner->user->name,-1) }}</span>
                                <span class="text-[9px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 rounded px-1.5 py-0.5 font-mono tracking-wide">{{ $winner->ticket_number }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-[12px] font-black text-emerald-600 italic" style="font-family:'Syne',sans-serif">+৳{{ number_format($winner->prize_amount, 0) }}</span>

                            {{-- Eye button — only for 4th and 5th tier --}}
                            @if($winner->prize_tier_id >= 4)
                            <button
                                type="button"
                                class="w-6 h-6 rounded-md border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-colors flex-shrink-0"
                                title="View winner details"
                                onclick="openWinnerModal({
                                    tier: '{{ $tiers[$winner->prize_tier_id] ?? 'Winner' }}',
                                    drawNo: '#{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}',
                                    draw: '{{ addslashes($draw->title) }}',
                                    name: '{{ substr($winner->user->name,0,3) }}***{{ substr($winner->user->name,-1) }}',
                                    ticket: '{{ $winner->ticket_number }}',
                                    prize: '৳{{ number_format($winner->prize_amount, 0) }}',
                                    pool: '৳{{ number_format($draw->prize_pool_total, 0) }}',
                                    date: '{{ $draw->draw_time->format('M d, Y') }}',
                                    rank: '{{ $loop->iteration }} of {{ $sortedWinners->count() }}'
                                })"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($sortedWinners->count() > 5)
                <p class="text-center text-[8px] font-semibold text-slate-400 uppercase tracking-widest mt-3">
                    ... and {{ $sortedWinners->count() - 5 }} other winners rewarded
                </p>
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

{{-- Winner Detail Modal --}}
<div id="winner-modal-overlay"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200"
     style="background:rgba(0,0,0,.44)"
     onclick="if(event.target===this)closeWinnerModal()">
    <div id="winner-modal-box"
         class="bg-white rounded-[1.1rem] border border-slate-200 w-full max-w-sm overflow-hidden shadow-2xl scale-95 translate-y-2 transition-transform duration-200">

        {{-- Modal dark header --}}
        <div class="relative overflow-hidden px-5 py-4" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
            <div class="absolute -right-3 -top-3 w-14 h-14 rounded-full pointer-events-none" style="background:rgba(37,99,235,.14)"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <span id="modal-draw-no" class="inline-block text-[8px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 border border-white/10 rounded px-2 py-0.5 mb-1.5"></span>
                    <div id="modal-tier" class="text-[15px] font-black text-white tracking-tight italic" style="font-family:'Syne',sans-serif"></div>
                </div>
                <button onclick="closeWinnerModal()" class="w-7 h-7 bg-white/7 border border-white/10 rounded-lg flex items-center justify-center text-slate-400 hover:bg-white/14 hover:text-white transition-colors flex-shrink-0 ml-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            {{-- Prize banner --}}
            <div class="mt-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg px-3 py-2.5 flex items-center gap-3 relative z-10">
                <div class="text-xl leading-none" id="modal-emoji">🎖️</div>
                <div>
                    <span class="block text-[7px] font-bold text-emerald-300 uppercase tracking-widest mb-0.5">Prize Awarded</span>
                    <span id="modal-prize" class="text-[19px] font-black text-emerald-400 italic leading-none" style="font-family:'Syne',sans-serif"></span>
                </div>
            </div>
        </div>

        {{-- Modal fields --}}
        <div class="px-5 py-4 divide-y divide-slate-100">
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Winner</span>
                <span id="modal-name" class="text-[11px] font-bold text-slate-800 italic" style="font-family:'Syne',sans-serif"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ticket No.</span>
                <span id="modal-ticket" class="text-[10px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 rounded px-2 py-0.5 font-mono tracking-wide"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Draw</span>
                <span id="modal-draw-name" class="text-[11px] font-bold text-slate-800" style="font-family:'Syne',sans-serif"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Draw Date</span>
                <span id="modal-date" class="text-[11px] font-bold text-slate-800"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Prize Pool</span>
                <span id="modal-pool" class="text-[11px] font-bold text-blue-600"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Overall Rank</span>
                <span id="modal-rank" class="text-[11px] font-bold text-slate-800"></span>
            </div>
        </div>

        <div class="px-5 pb-4">
            <button onclick="closeWinnerModal()" class="w-full py-2.5 rounded-xl text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-600 hover:bg-white transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function openWinnerModal(d) {
    var emojis = {'1st / Grand Prize':'🥇','2nd Prize':'🥈','3rd Prize':'🥉'};
    document.getElementById('modal-draw-no').textContent   = 'Draw Protocol ' + d.drawNo;
    document.getElementById('modal-tier').textContent      = d.tier;
    document.getElementById('modal-emoji').textContent     = emojis[d.tier] || '🎖️';
    document.getElementById('modal-prize').textContent     = '+' + d.prize;
    document.getElementById('modal-name').textContent      = d.name;
    document.getElementById('modal-ticket').textContent    = d.ticket;
    document.getElementById('modal-draw-name').textContent = d.draw;
    document.getElementById('modal-date').textContent      = d.date;
    document.getElementById('modal-pool').textContent      = d.pool;
    document.getElementById('modal-rank').textContent      = 'Winner ' + d.rank;
    var ov = document.getElementById('winner-modal-overlay');
    var bx = document.getElementById('winner-modal-box');
    ov.classList.remove('opacity-0','pointer-events-none');
    ov.classList.add('opacity-100');
    bx.classList.remove('scale-95','translate-y-2');
    bx.classList.add('scale-100','translate-y-0');
}
function closeWinnerModal() {
    var ov = document.getElementById('winner-modal-overlay');
    var bx = document.getElementById('winner-modal-box');
    ov.classList.add('opacity-0','pointer-events-none');
    ov.classList.remove('opacity-100');
    bx.classList.add('scale-95','translate-y-2');
    bx.classList.remove('scale-100','translate-y-0');
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeWinnerModal(); });
</script>
</x-app-layout>