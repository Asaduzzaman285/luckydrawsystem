@php
    $maxWidth = 'sm:max-w-6xl';
@endphp

<x-guest-layout :maxWidth="$maxWidth">
    <div class="py-4">
        <div class="flex justify-between items-end mb-8 border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tighter italic">win / <span class="text-blue-600">history</span></h1>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Previous lucky draw winners</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Updated: {{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($draws as $draw)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 hover:border-blue-200 transition duration-300 group shadow-sm hover:shadow-md">
                    <div class="p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-black text-slate-900 tracking-tight leading-tight">{{ $draw->title }}</h3>
                            <span class="text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100 italic">#{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-50">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Sales</span>
                                <span class="text-xs font-bold text-slate-900 italic">৳{{ number_format($draw->total_sales, 2) }}</span>
                            </div>
                            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100/50">
                                <span class="block text-[8px] font-black text-blue-600 uppercase tracking-widest mb-1">Prize Pool</span>
                                <span class="text-xs font-bold text-blue-600 italic">৳{{ number_format($draw->prize_pool_total, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                             <div class="text-[9px] text-slate-400 font-bold italic">{{ $draw->draw_time->format('M d, Y') }}</div>
                             <a href="{{ route('results.show', $draw->id) }}" class="text-[9px] font-black uppercase tracking-widest text-slate-900 group-hover:text-blue-600 transition-colors flex items-center">
                                View winners <span class="ml-1 text-lg">→</span>
                             </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold text-sm italic">No draw results found yet.</p>
                </div>
            @endforelse
        </div>

        @if($draws->hasPages())
            <div class="mt-8">
                {{ $draws->links() }}
            </div>
        @endif
    </div>
</x-guest-layout>
