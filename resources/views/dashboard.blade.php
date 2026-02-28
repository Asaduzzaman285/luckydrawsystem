<x-app-layout>
    <div class="min-h-screen bg-gray-900 pb-24">
        
        <!-- bKash Pro Header -->
        <div class="bg-gradient-to-r from-[#e2136e] to-[#9d0a4d] pt-8 pb-20 px-4 sm:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-black text-xl italic">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">member account</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Balance Toggle Pill -->
                    <div x-data="{ show: false }" @click="show = !show" class="balance-pill group scale-90 sm:scale-100 origin-right">
                        <div class="w-7 h-7 bg-bkash rounded-full flex items-center justify-center text-white text-[10px] font-black italic">৳</div>
                        <div class="relative flex-1 overflow-hidden h-5">
                            <div x-show="!show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-[10px] font-black text-bkash uppercase tracking-widest pt-0.5">tap for balance</div>
                            <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-sm font-black text-bkash tracking-tighter pt-0">৳ {{ number_format($wallet->balance ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Negative margin up) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- User Service Grid -->
            <div class="bg-maroon rounded-3xl p-6 shadow-2xl mb-8 border border-white/10 text-center">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <!-- Grid Items -->
                    <a href="#live-draws" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-pink-600 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">🎟️</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 text-center group-hover:text-white">participate</span>
                    </a>
                    <a href="{{ route('results.index') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-blue-600 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">📊</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 text-center group-hover:text-white">results</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gray-700 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">🆔</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 text-center group-hover:text-white">identity</span>
                    </a>
                    @role('agent')
                    <a href="{{ route('agent.dashboard') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-amber-500 flex items-center justify-center text-xl sm:text-2xl text-white transition-all duration-300 shadow-lg border border-white/20">💼</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-white/80 uppercase tracking-widest mt-3 text-center group-hover:text-white">terminal</span>
                    </a>
                    @endrole
                </div>
            </div>

            <!-- Hero Live Showcase -->
            <div id="live-draws" class="mb-10">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h2 class="text-xs font-black text-white uppercase tracking-[0.2em] italic">live / <span class="text-bkash">showcase</span></h2>
                    <div class="h-px bg-white/10 flex-1 mx-4"></div>
                </div>

                @forelse($liveProducts as $product)
                    <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 group mb-8 text-white">
                        <div class="flex flex-col lg:flex-row">
                            <div class="lg:w-1/2 relative h-64 lg:h-auto overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                                @else
                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center text-bkash text-4xl font-black italic">NO ASSET</div>
                                @endif
                                <div class="absolute top-6 left-6">
                                    <span class="bg-bkash/90 backdrop-blur-md text-white text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 italic shadow-lg">Featured Drawing</span>
                                </div>
                            </div>
                            <div class="lg:w-1/2 p-8 sm:p-12">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="text-3xl font-black text-white tracking-tighter leading-tight italic lowercase mb-2">{{ $product->name }}</h4>
                                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Entry Limit: {{ $product->draw->max_tickets }} Tickets</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-4xl font-black text-bkash tracking-tighter leading-none"><span class="text-lg mr-0.5">৳</span>{{ number_format($product->price, 2) }}</div>
                                        <div class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 italic">Value / Unit</div>
                                    </div>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-6 mb-8 border border-white/10 shadow-inner">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-[10px] font-black text-white/60 uppercase tracking-widest italic flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span> Terminal Closing In</span>
                                        <span class="text-white font-black text-sm tracking-tight font-mono" id="countdown-{{ $product->id }}" data-time="{{ $product->draw->end_time->toIso8601String() }}">--:--:--</span>
                                    </div>
                                    <div class="relative w-full bg-white/10 h-2.5 rounded-full overflow-hidden shadow-sm">
                                        <div class="bg-gradient-to-r from-bkash to-pink-400 h-full transition-all duration-1000 shadow-[0_0_10px_rgba(226,19,110,0.5)]" style="width: {{ ($product->draw->sold_tickets / max(1, $product->draw->max_tickets)) * 100 }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-2 text-[8px] font-black text-white/40 uppercase tracking-[0.2em]">
                                        <span>Capacity: {{ $product->draw->sold_tickets }} sold</span>
                                        <span>{{ max(0, $product->draw->max_tickets - $product->draw->sold_tickets) }} remaining</span>
                                    </div>
                                </div>

                                <form action="{{ route('products.buy', $product) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-4">
                                        <div class="w-24">
                                            <input type="number" name="quantity" value="1" min="1" class="w-full bg-white/10 border-transparent rounded-xl py-4 text-white font-black text-center focus:ring-bkash transition text-sm">
                                        </div>
                                        <button type="submit" class="flex-1 bg-bkash text-white text-[11px] font-black py-4 rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-pink-500/20 hover:bg-white hover:text-bkash transition-all duration-300">
                                            Request Entries
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-maroon rounded-3xl p-12 text-center border border-dashed border-white/20">
                        <p class="text-white/40 font-bold uppercase tracking-widest italic text-xs">No live raffle opportunities available at this moment</p>
                    </div>
                @endforelse
            </div>

            <!-- Participation History (High Density Table) -->
            <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 text-white">
                <div class="px-8 py-5 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em] italic">Previous / <span class="text-white/60 italic font-bold">Participations</span></h3>
                    <a href="{{ route('results.index') }}" class="text-[9px] font-black text-bkash uppercase tracking-widest hover:underline">View All Winners</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Protocol ID</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Identity Segment</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($tickets as $transactionId => $ticketGroup)
                                @php 
                                    $firstTicket = $ticketGroup->first();
                                    $hasWinner = $ticketGroup->contains('is_winner', true);
                                @endphp
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-8 py-4">
                                        <div class="font-black text-white text-xs tracking-tight">{{ $firstTicket->draw->title }}</div>
                                        <div class="text-[9px] text-white/40 font-bold mt-1 italic">{{ $firstTicket->product->name ?? 'Direct' }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex flex-wrap gap-1.5 max-w-sm">
                                            @foreach($ticketGroup->take(5) as $ticket)
                                                <span class="text-[9px] font-black {{ $ticket->is_winner ? 'bg-bkash text-white' : 'bg-white/10 text-white/60' }} px-2 py-0.5 rounded uppercase tracking-tighter">
                                                    {{ substr($ticket->ticket_number, -6) }}
                                                </span>
                                            @endforeach
                                            @if($ticketGroup->count() > 5)
                                                <span class="text-[8px] font-black text-white/20 uppercase tracking-tighter self-center">+{{ $ticketGroup->count() - 5 }} more</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if($hasWinner)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-500/20 text-emerald-400 uppercase tracking-widest animate-pulse">Winner Identified</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-white/10 text-white/40 uppercase tracking-widest">{{ $firstTicket->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="text-[9px] font-black text-white/40 uppercase tracking-[0.1em]">{{ $firstTicket->created_at->format('d M, Y') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-white/20 italic font-bold uppercase tracking-widest text-[9px]">Zero Activity Records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Persistent Bottom Nav (Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-white/5 px-6 py-4 sm:hidden z-50 flex justify-between items-center shadow-[0_-10px_30px_rgba(0,0,0,0.2)] rounded-t-[2rem]">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1">
            <div class="w-8 h-8 rounded-full bg-bkash flex items-center justify-center text-white shadow-lg shadow-pink-500/20">🏠</div>
            <span class="text-[8px] font-black text-bkash uppercase tracking-widest">Home</span>
        </a>
        <a href="{{ route('results.index') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">🏆</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Wins</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">🔔</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Alerts</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 opacity-50">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">👤</div>
            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Me</span>
        </a>
    </div>

    @push('scripts')
    <script>
        function updateCountdowns() {
            document.querySelectorAll('[id^="countdown-"]').forEach(el => {
                const endTime = new Date(el.dataset.time).getTime();
                const now = new Date().getTime();
                const diff = endTime - now;

                if (diff <= 0) {
                    el.innerHTML = "EXPIRED";
                    el.classList.add('text-red-500');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                let text = "";
                if (days > 0) text += `${days}d `;
                text += `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                el.innerHTML = text;
            });
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>
    @endpush
</x-app-layout>

