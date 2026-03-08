<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24" x-data="{ agentModal: false }">
        
        <!-- Professional Header -->
        <div class="bg-gradient-to-r from-[#1a56db] to-[#1e3a8a] pt-8 pb-20 px-4 sm:px-8 shadow-inner">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center shadow-lg">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-black text-xl italic shadow-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest italic">member account</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none tracking-tighter">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Balance Toggle Pill -->
                    <div x-data="{ show: false }" class="flex items-center space-x-2">
                        <div @click="show = !show" class="balance-pill group scale-90 sm:scale-100 origin-right cursor-pointer bg-white/10 border-white/10 backdrop-blur-md">
                            <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-black italic shadow-lg">৳</div>
                            <div class="relative flex-1 overflow-hidden h-5 ml-2">
                                <div x-show="!show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-[10px] font-black text-white uppercase tracking-widest pt-0.5 italic">tap for balance</div>
                                <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4" class="text-sm font-black text-white tracking-tighter pt-0 italic">৳ {{ number_format($wallet->balance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <button @click="$dispatch('open-withdraw-modal')" class="bg-white/10 hover:bg-white/20 text-white text-[8px] font-black px-4 py-2.5 rounded-xl uppercase tracking-widest transition shadow-lg border border-white/10 italic">
                            Withdraw
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @php 
            $associateAgent = Auth::user()->agent ?? (Auth::user()->creator && Auth::user()->creator->hasRole('agent') ? Auth::user()->creator : null);
        @endphp

        <!-- Agent Modal -->
        <div x-show="agentModal" 
             class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white w-full max-w-2xl rounded-[3rem] p-10 shadow-2xl relative border border-white" @click.away="agentModal = false">
                <button @click="agentModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-blue-600 transition font-black text-xl">✕</button>
                
                @if($associateAgent)
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter lowercase italic mb-2">associate / <span class="text-blue-600">agent</span></h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Your assigned regional representative</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                    <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-4xl font-black text-white italic shadow-xl border-2 border-white transform -rotate-3">
                        {{ substr($associateAgent->name, 0, 1) }}
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <div class="text-3xl font-black text-slate-900 tracking-tighter italic lowercase">{{ $associateAgent->name }}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 shadow-inner">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Official Contact</div>
                                <div class="text-sm font-black text-slate-900 tracking-tight italic">{{ $associateAgent->phone ?? 'Not Available' }}</div>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 shadow-inner">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Email Address</div>
                                <div class="text-sm font-black text-slate-900 tracking-tight truncate italic">{{ $associateAgent->email ?? 'Not Available' }}</div>
                            </div>
                            @if($associateAgent->district)
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 shadow-inner">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Region</div>
                                <div class="text-sm font-black text-slate-900 tracking-tight italic">{{ $associateAgent->district->name }}</div>
                            </div>
                            @endif
                            @if($associateAgent->upazilla)
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 shadow-inner">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Local Area</div>
                                <div class="text-sm font-black text-slate-900 tracking-tight italic">{{ $associateAgent->upazilla->name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-10">
                    @if($associateAgent->phone)
                    <a href="tel:{{ $associateAgent->phone }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-3 italic">
                        <span>📞</span> CALL AGENT
                    </a>
                    @endif
                    @if($associateAgent->email)
                    <a href="mailto:{{ $associateAgent->email }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-3 italic">
                        <span>✉️</span> EMAIL AGENT
                    </a>
                    @endif
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-5xl mb-4">❓</div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-2">No Agent Assigned</h3>
                    <p class="text-sm text-slate-400 font-bold mb-8">Your account is currently not linked to a regional representative.</p>
                    <a href="mailto:admin@luckydraw.pro" class="inline-flex bg-blue-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg italic">Contact Admin</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Withdrawal Modal -->
        <div x-data="{ open: false }" 
             @open-withdraw-modal.window="open = true" 
             x-show="open" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" 
             x-cloak>
            <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative border border-white" @click.away="open = false">
                <button @click="open = false" class="absolute top-8 right-8 text-slate-300 hover:text-blue-600 transition font-black text-xl">✕</button>
                
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter italic lowercase">request / <span class="text-blue-600">withdrawal</span></h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2">Transfer digital balance to cash via agent</p>
                </div>

                @if($associateAgent)
                <form action="{{ route('withdraw.request') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-2 italic">Amount to Withdraw (৳)</label>
                        <input type="number" name="amount" step="0.01" min="1" max="{{ $wallet->balance ?? 0 }}" 
                               class="w-full bg-slate-50 border-slate-200 rounded-2xl py-5 px-6 font-black text-slate-900 focus:ring-blue-600 transition text-center text-2xl shadow-inner" 
                               placeholder="0.00" required>
                        <div class="text-center mt-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Available Balance: <span class="text-blue-600">৳{{ number_format($wallet->balance ?? 0, 2) }}</span></span>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100 shadow-inner">
                        <div class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1 italic">Target Agent</div>
                        <div class="text-sm font-black text-slate-900 tracking-tight italic">{{ $associateAgent->name }}</div>
                        <div class="text-[8px] text-slate-400 font-bold uppercase mt-1">Cash will be provided by this agent after approval</div>
                    </div>

                    <input type="hidden" name="target_agent_id" value="{{ $associateAgent->id }}">
                    <input type="hidden" name="payment_method" value="Agent Cash">
                    <input type="hidden" name="account_details" value="Personal Pickup">

                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition uppercase tracking-widest text-[10px] italic">
                        Confirm Request
                    </button>
                </form>
                @else
                <div class="bg-slate-50 rounded-2xl p-8 text-center border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase tracking-widest italic text-[10px]">No associate agent found. Please contact support to assign an agent before withdrawing.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Main Content (Negative margin up) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- User Service Grid -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 mb-8 border border-white text-center">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <!-- Grid Items -->
                    <a href="#live-draws" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-xl sm:text-2xl text-blue-600 transition-all duration-300 shadow-sm border border-blue-100/50 group-hover:bg-blue-600 group-hover:text-white">🎟️</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 text-center group-hover:text-blue-600 leading-tight">participate</span>
                    </a>
                    <a href="{{ route('results.index') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl sm:text-2xl text-indigo-600 transition-all duration-300 shadow-sm border border-indigo-100/50 group-hover:bg-indigo-600 group-hover:text-white">📊</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 text-center group-hover:text-indigo-600 leading-tight">results</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-xl sm:text-2xl text-slate-600 transition-all duration-300 shadow-sm border border-slate-100/50 group-hover:bg-slate-600 group-hover:text-white">🆔</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 text-center group-hover:text-slate-900 leading-tight">identity</span>
                    </a>
                    
                    <button @click="agentModal = true" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-xl sm:text-2xl text-emerald-600 transition-all duration-300 shadow-sm border border-emerald-100/50 group-hover:bg-emerald-600 group-hover:text-white">🤝</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 text-center group-hover:text-emerald-600 leading-tight">my agent</span>
                    </button>

                    @role('agent')
                    <a href="{{ route('agent.dashboard') }}" class="flex flex-col items-center group">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-xl sm:text-2xl text-amber-500 transition-all duration-300 shadow-sm border border-amber-100/50 group-hover:bg-amber-500 group-hover:text-white">💼</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-3 text-center group-hover:text-amber-600 leading-tight">terminal</span>
                    </a>
                    @endrole
                </div>
            </div>

            <!-- Hero Live Showcase (Slider) -->
            <div id="live-draws" class="mb-10 relative" x-data="{ 
                active: 0,
                total: {{ $liveProducts->count() }},
                next() { this.active = this.active === this.total - 1 ? 0 : this.active + 1; this.scroll(); },
                prev() { this.active = this.active === 0 ? this.total - 1 : this.active - 1; this.scroll(); },
                scroll() {
                    const slider = this.$refs.slider;
                    const card = slider.firstElementChild;
                    if (!card) return;
                    const cardWidth = card.offsetWidth + 24; // width + gap
                    slider.scrollTo({ left: this.active * cardWidth, behavior: 'smooth' });
                }
            }">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] italic">live / <span class="text-blue-600">showcase</span></h2>
                    <div class="flex items-center space-x-3">
                        <button @click="prev()" class="w-10 h-10 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="next()" class="w-10 h-10 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Slider Container -->
                <div class="overflow-hidden">
                    <div x-ref="slider" class="flex gap-6 overflow-x-auto pb-8 snap-x snap-mandatory no-scrollbar scroll-smooth">
                        @forelse($liveProducts as $product)
                            <div class="flex-none w-full md:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] snap-center">
                                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-slate-100 group transition-all duration-500 hover:border-blue-200 h-full flex flex-col">
                                    <div class="relative h-48 overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                        @else
                                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-blue-100 text-3xl font-black italic">ASSET</div>
                                        @endif
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-blue-600 text-white text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest border border-white/20 italic shadow-lg">Active</span>
                                        </div>
                                    </div>
                                    <div class="p-6 flex flex-col flex-1">
                                        <div class="mb-4">
                                            <h4 class="text-xl font-black text-slate-900 tracking-tighter leading-tight italic lowercase truncate">{{ $product->name }}</h4>
                                            <div class="flex justify-between items-center mt-2">
                                                <div class="text-2xl font-black text-blue-600 tracking-tighter italic">৳{{ number_format($product->price, 0) }}</div>
                                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Entry Fee</div>
                                            </div>
                                        </div>

                                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-100 shadow-inner">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic flex items-center"><span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2 animate-pulse shadow-[0_0_5px_rgba(239,68,68,0.5)]"></span> Closes In</span>
                                                <span class="text-slate-900 font-black text-[10px] tracking-tight font-mono italic" id="countdown-{{ $product->id }}" data-time="{{ $product->draw->end_time->toIso8601String() }}">--:--:--</span>
                                            </div>
                                            <div class="relative w-full bg-white h-2 rounded-full overflow-hidden border border-slate-100">
                                                <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-full shadow-[0_0_10px_rgba(37,99,235,0.2)]" style="width: {{ ($product->draw->sold_tickets / max(1, $product->draw->max_tickets)) * 100 }}%"></div>
                                            </div>
                                        </div>

                                        <form action="{{ route('products.buy', $product) }}" method="POST" class="mt-auto">
                                            @csrf
                                            <div class="flex gap-3">
                                                <input type="number" name="quantity" value="1" min="1" class="w-16 bg-slate-50 border-slate-200 rounded-xl py-3 text-slate-900 font-black text-center focus:ring-blue-600 transition text-xs shadow-inner">
                                                <button type="submit" class="flex-1 bg-blue-600 text-white text-[10px] font-black py-3 rounded-xl uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:bg-slate-900 transition-all duration-300 italic">
                                                    Enter Draw
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full bg-white rounded-[2rem] p-12 text-center border border-dashed border-slate-200 shadow-inner">
                                <p class="text-slate-300 font-black uppercase tracking-widest italic text-sm">No live raffle opportunities available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Participation History (High Density Table) -->
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-100 text-slate-900">
                <div class="px-10 py-7 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Previous / <span class="text-blue-600 italic font-bold">Participations</span></h3>
                    <a href="{{ route('results.index') }}" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline italic">View All Winners</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 italic">Protocol ID</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 italic">Identity Segment</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 italic">Status</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 italic text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tickets as $transactionId => $ticketGroup)
                                @php 
                                    $firstTicket = $ticketGroup->first();
                                    $hasWinner = $ticketGroup->contains('is_winner', true);
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-10 py-6">
                                        <div class="font-black text-slate-900 text-sm tracking-tight italic">{{ $firstTicket->draw->title }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold mt-1 italic tracking-widest uppercase">{{ $firstTicket->product->name ?? 'Direct' }}</div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-wrap gap-2 max-w-sm">
                                            @foreach($ticketGroup->take(5) as $ticket)
                                                <span class="text-[9px] font-black {{ $ticket->is_winner ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }} px-3 py-1 rounded uppercase tracking-tighter italic border border-slate-200/50 shadow-sm">
                                                    {{ substr($ticket->ticket_number, -6) }}
                                                </span>
                                            @endforeach
                                            @if($ticketGroup->count() > 5)
                                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-tighter self-center italic ml-1">+{{ $ticketGroup->count() - 5 }} more</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        @if($hasWinner)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[8px] font-black bg-emerald-500/10 text-emerald-600 uppercase tracking-widest animate-pulse border border-emerald-500/20 italic">Winner Identified</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded text-[8px] font-black bg-slate-50 text-slate-400 uppercase tracking-widest italic border border-slate-100">{{ $firstTicket->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] italic">{{ $firstTicket->created_at->format('d M, Y') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-10 py-16 text-center text-slate-200 italic font-bold uppercase tracking-widest text-[10px]">Zero Activity Records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Persistent Bottom Nav (Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 px-8 py-5 sm:hidden z-50 flex justify-between items-center shadow-[0_-15px_40px_rgba(0,0,0,0.05)] rounded-t-[2.5rem]">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1.5">
            <div class="w-9 h-9 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-xl shadow-blue-500/30">🏠</div>
            <span class="text-[8px] font-black text-blue-600 uppercase tracking-widest italic">Home</span>
        </a>
        <a href="{{ route('results.index') }}" class="flex flex-col items-center gap-1.5 opacity-40">
            <div class="w-9 h-9 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">🏆</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Wins</span>
        </a>
        <button @click="agentModal = true" class="flex flex-col items-center gap-1.5 opacity-40">
            <div class="w-9 h-9 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">🤝</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Agent</span>
        </button>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1.5 opacity-40">
            <div class="w-9 h-9 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">👤</div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Me</span>
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
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @endpush
</x-app-layout>
