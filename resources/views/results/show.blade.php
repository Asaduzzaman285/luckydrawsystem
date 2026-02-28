@php
    $maxWidth = 'sm:max-w-5xl';
@endphp

<x-guest-layout :maxWidth="$maxWidth">
    <div class="py-4 px-2">
        <div class="mb-6">
            <a href="{{ route('results.index') }}" class="text-[10px] text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest flex items-center mb-4 transition">
                <span>← Back to Archives</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm mb-6 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter mb-1">{{ $draw->title }}</h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic">Winners announced on {{ $draw->draw_time->format('M d, Y') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100/50 min-w-32">
                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Sales</div>
                        <div class="text-xl font-black text-slate-900 tracking-tight">${{ number_format($draw->total_sales, 2) }}</div>
                    </div>
                    <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100/50 min-w-32">
                        <div class="text-[9px] text-amber-500 font-black uppercase tracking-widest mb-1">Total Prize Pool</div>
                        <div class="text-xl font-black text-amber-600 tracking-tight">${{ number_format($draw->prize_pool_total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @php
                $tierNames = [
                    1 => ['name' => '1st Prize / Grand Winner', 'color' => 'bg-amber-400', 'text' => 'text-slate-900', 'prize' => '30%'],
                    2 => ['name' => '2nd Prize / Runner Up', 'color' => 'bg-slate-200', 'text' => 'text-slate-600', 'prize' => '10%'],
                    3 => ['name' => '3rd Prize', 'color' => 'bg-amber-100', 'text' => 'text-amber-800', 'prize' => '7%'],
                    4 => ['name' => 'Lucky Draw / Matching Digit', 'color' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'prize' => '5%'],
                    5 => ['name' => 'Fortune Wheel / Bulk Reward', 'color' => 'bg-blue-50', 'text' => 'text-blue-700', 'prize' => '3%'],
                ];
            @endphp

            @foreach($tieredWinners as $tierId => $winners)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                    <div class="{{ $tierNames[$tierId]['color'] }} {{ $tierNames[$tierId]['text'] }} px-6 py-2.5 flex justify-between items-center border-b border-slate-100/10">
                        <h3 class="font-black uppercase tracking-widest text-[10px]">{{ $tierNames[$tierId]['name'] }}</h3>
                        <span class="text-[9px] font-black opacity-60 italic">{{ $tierNames[$tierId]['prize'] }} of pool</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($winners as $winner)
                                <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100 flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center text-sm">🏆</div>
                                    <div>
                                        <div class="text-[11px] font-black text-slate-900 tracking-tight">
                                            {{ substr($winner->user->name, 0, 3) }}***{{ substr($winner->user->name, -2) }}
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">TKT: {{ substr($winner->ticket_number, -6) }}</div>
                                        <div class="text-amber-600 font-black text-xs mt-0.5">${{ number_format($winner->prize_amount, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-guest-layout>
