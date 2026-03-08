@php
    $maxWidth = 'sm:max-w-5xl';
@endphp

<x-guest-layout :maxWidth="$maxWidth">
    <div class="py-4 px-2">
        <div class="mb-6">
            <a href="{{ route('results.index') }}" class="text-[10px] text-slate-400 hover:text-blue-600 font-black uppercase tracking-widest flex items-center mb-4 transition italic">
                <span>← Back to Archives</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-blue-900/5 mb-6 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter mb-1 italic">{{ $draw->title }}</h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic">Winners announced on {{ $draw->draw_time->format('M d, Y') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 min-w-32 shadow-inner">
                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Sales</div>
                        <div class="text-xl font-black text-slate-900 tracking-tight italic">৳{{ number_format($draw->total_sales, 2) }}</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 min-w-32 shadow-inner">
                        <div class="text-[9px] text-blue-600 font-black uppercase tracking-widest mb-1">Total Prize Pool</div>
                        <div class="text-xl font-black text-blue-600 tracking-tight italic">৳{{ number_format($draw->prize_pool_total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @php
                $tierNames = [
                    1 => ['name' => '1st Prize / Grand Winner', 'color' => 'bg-blue-600', 'text' => 'text-white', 'prize' => '30%'],
                    2 => ['name' => '2nd Prize / Runner Up', 'color' => 'bg-blue-100', 'text' => 'text-blue-800', 'prize' => '10%'],
                    3 => ['name' => '3rd Prize', 'color' => 'bg-slate-100', 'text' => 'text-slate-800', 'prize' => '7%'],
                    4 => ['name' => 'Lucky Draw / Matching Digit', 'color' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'prize' => '5%'],
                    5 => ['name' => 'Fortune Wheel / Bulk Reward', 'color' => 'bg-blue-50', 'text' => 'text-blue-700', 'prize' => '3%'],
                ];
            @endphp

            @foreach($tieredWinners as $tierId => $winners)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-xl shadow-blue-900/5">
                    <div class="{{ $tierNames[$tierId]['color'] }} {{ $tierNames[$tierId]['text'] }} px-8 py-3 flex justify-between items-center border-b border-slate-100/10">
                        <h3 class="font-black uppercase tracking-widest text-[10px] italic">{{ $tierNames[$tierId]['name'] }}</h3>
                        <span class="text-[9px] font-black opacity-60 italic">{{ $tierNames[$tierId]['prize'] }} of pool</span>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($winners as $winner)
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center space-x-3 transition hover:border-blue-200">
                                    <div class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center text-sm border border-slate-100">🏆</div>
                                    <div>
                                        <div class="text-[11px] font-black text-slate-900 tracking-tight italic">
                                            {{ substr($winner->user->name, 0, 3) }}***{{ substr($winner->user->name, -2) }}
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">TKT: {{ substr($winner->ticket_number, -6) }}</div>
                                        <div class="text-blue-600 font-black text-xs mt-0.5">৳{{ number_format($winner->prize_amount, 2) }}</div>
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
