<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24" x-data="{ 
            winnerModal: false,
            selectedTier: 1, 
            selectionMethod: 'manual',
            ticketNumber: '', 
            autoUserName: '',
            loading: false, 
            previewData: null,
            error: null,
            winners: {{ json_encode($winners->mapWithKeys(fn($w, $k) => [$k => true])) }},
            tierNames: {
                1: '1st Prize / Grand Winner',
                2: '2nd Prize / Runner Up',
                3: '3rd Prize',
                4: '4th Prize / Lucky Selection',
                5: '5th Prize / Fortune Selection'
            },
            async fetchRandomTicket() {
                this.loading = true;
                this.error = null;
                this.ticketNumber = '';
                this.autoUserName = '';
                try {
                    const response = await fetch(`/draws/{{ $draw->id }}/random-ticket`);
                    const data = await response.json();
                    if (data.error) throw new Error(data.error);
                    this.ticketNumber = data.ticket_number;
                    this.autoUserName = data.user_name;
                } catch (e) {
                    this.error = e.message;
                } finally {
                    this.loading = false;
                }
            },
            async fetchPreview() {
                this.loading = true;
                this.error = null;
                this.previewData = null;
                try {
                    const response = await fetch(`/draws/{{ $draw->id }}/preview/${this.selectedTier}`);
                    const data = await response.json();
                    // Don't throw if no tickets, just show the empty state
                    this.previewData = data;
                } catch (e) {
                    this.error = e.message;
                } finally {
                    this.loading = false;
                }
            }
        }">
        
        <!-- Professional Header -->
        <div class="bg-gradient-to-r from-[#1a56db] to-[#1e3a8a] pt-8 pb-20 px-4 sm:px-8 shadow-inner">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center text-white font-black text-xl italic shadow-lg">
                            🏆
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">winner console</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none italic uppercase">{{ $draw->title }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if($draw->status !== 'completed')
                            <form action="{{ route('draws.finalize', $draw->id) }}" method="POST" onsubmit="return confirm('Finalize this draw? No more changes allowed.')">
                                @csrf
                                <button type="submit" class="bg-white text-blue-600 text-[10px] font-black px-6 py-3 rounded-xl uppercase tracking-widest hover:bg-blue-50 transition shadow-lg italic">Finalize Draw</button>
                            </form>
                        @else
                            <span class="bg-white/10 text-white/60 text-[10px] font-black px-6 py-3 rounded-xl uppercase tracking-widest border border-white/20">Completed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Financial Protocol Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 border border-white flex items-center justify-between">
                    <div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Sales Revenue</div>
                        <div class="text-2xl font-black text-slate-900 tracking-tighter italic">৳ {{ number_format($draw->total_sales, 2) }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">💰</div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 border border-white flex items-center justify-between">
                    <div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Prize Pool (55%)</div>
                        <div class="text-2xl font-black text-emerald-600 tracking-tighter italic">৳ {{ number_format($draw->prize_pool_total, 2) }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-xl">🎁</div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 border border-white flex items-center justify-between">
                    <div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tickets Sold</div>
                        <div class="text-2xl font-black text-blue-600 tracking-tighter italic">{{ $draw->sold_tickets }} <span class="text-xs text-slate-300">/ {{ $draw->max_tickets ?? '∞' }}</span></div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">🎟️</div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-600 font-bold flex items-center space-x-3 animate-fade-in text-xs uppercase tracking-tight italic">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Winner Console Action -->
            <div class="mb-10 flex justify-center">
                @if($draw->status !== 'completed')
                    <button @click="winnerModal = true" class="bg-[#1e3a8a] text-white text-[10px] font-black px-12 py-5 rounded-3xl uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/30 hover:scale-[1.02] transition-all transform italic">
                        Open Winner Selection Modal
                    </button>
                @else
                    <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-white px-12 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Draw Completed / Results Finalized
                    </div>
                @endif
            </div>

            <!-- Winner Selection Modal -->
            <div x-show="winnerModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                
                <div class="bg-white w-full max-w-4xl rounded-[3rem] p-12 shadow-2xl relative border border-white" @click.away="winnerModal = false">
                    <button @click="winnerModal = false" class="absolute top-10 right-10 text-slate-300 hover:text-blue-600 transition font-black text-xl">✕</button>
                    
                    <div class="mb-10">
                        <h3 class="text-3xl font-black text-slate-900 tracking-tighter lowercase italic">award / <span class="text-blue-600">prize</span></h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Manual Selection & Algorithmic Verification</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Step 1: Selection -->
                        <div class="space-y-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">Step 1: Select Target Tier</label>
                                <select x-model="selectedTier" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-5 px-8 text-sm font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner appearance-none">
                                    <template x-for="[id, name] in Object.entries(tierNames)">
                                        <option :value="id" x-text="name" :disabled="winners[id]" :class="winners[id] ? 'text-slate-300' : ''"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Selection Method (1-3) -->
                            <div x-show="selectedTier <= 3" class="mb-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">Selection Method</label>
                                <div class="flex space-x-2">
                                    <button @click="selectionMethod = 'manual'" :class="selectionMethod === 'manual' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition italic">Manual</button>
                                    <button @click="selectionMethod = 'auto'; fetchRandomTicket()" :class="selectionMethod === 'auto' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition italic">Automatic</button>
                                </div>
                            </div>

                            <!-- Manual Interaction (1-3) -->
                            <div x-show="selectedTier <= 3" class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic" x-text="selectionMethod === 'manual' ? 'Step 2: Assign Manual Ticket' : 'Step 2: Automatic Selection Result'"></label>
                                <form action="{{ route('draws.pick-tier', $draw->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tier_id" :value="selectedTier">
                                    <div class="relative">
                                        <input type="text" name="ticket_number" x-model="ticketNumber" placeholder="Enter Winning Code..." 
                                            class="w-full bg-slate-50 border-slate-200 rounded-2xl py-5 px-8 text-sm font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner" required :disabled="winners[selectedTier] || (selectionMethod === 'auto' && loading)">
                                        
                                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 bg-blue-600 text-white text-[10px] font-black px-8 py-3 rounded-xl uppercase tracking-widest hover:bg-blue-700 transition shadow-lg italic disabled:opacity-50" :disabled="winners[selectedTier] || !ticketNumber || (selectionMethod === 'auto' && loading)">Award Now</button>
                                    </div>
                                    <p x-show="autoUserName" class="mt-2 text-[10px] font-black text-blue-600 italic uppercase">Selected: <span x-text="autoUserName"></span></p>
                                </form>
                                <template x-if="winners[selectedTier]">
                                    <p class="text-[10px] font-black text-emerald-600 uppercase italic">✓ Tier already awarded.</p>
                                </template>
                            </div>

                            <!-- Algorithmic Interaction (4-5) -->
                            <div x-show="selectedTier >= 4" class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">Step 2: Run Selection Protocol</label>
                                <button @click="fetchPreview()" :disabled="loading || winners[selectedTier]" class="w-full bg-slate-900 text-white text-[10px] font-black py-5 rounded-2xl uppercase tracking-widest hover:bg-blue-600 transition shadow-xl disabled:opacity-50 italic">
                                    <span x-show="!loading && !winners[selectedTier]">Initialize Random Pick</span>
                                    <span x-show="loading">Scanning Ledger...</span>
                                    <span x-show="!loading && winners[selectedTier]">Tier Awarded</span>
                                </button>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100 min-h-[300px] flex flex-col shadow-inner">
                            <div x-show="!previewData && !error && !loading" class="flex-1 flex flex-col items-center justify-center text-center">
                                <div class="text-4xl mb-4 opacity-20">🎯</div>
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Waiting for selection...</p>
                            </div>

                            <div x-show="loading" class="flex-1 flex flex-col items-center justify-center">
                                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                            </div>

                            <template x-if="error">
                                <div class="bg-red-50 text-red-600 p-6 rounded-3xl text-[10px] font-black uppercase italic border border-red-100" x-text="error"></div>
                            </template>

                            <template x-if="previewData">
                                <div class="space-y-6 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase italic">selection preview</h4>
                                            <p class="text-[8px] text-slate-400 font-bold uppercase" x-text="`Tier ${selectedTier} Results`"></p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] font-black text-blue-600 uppercase italic" x-text="`৳ ${Number(previewData.total_prize).toLocaleString()}`"></div>
                                        </div>
                                    </div>

                                    <div class="space-y-3 overflow-y-auto max-h-[200px] pr-2">
                                        <template x-if="previewData.tickets.length === 0">
                                            <div class="text-center p-8 text-slate-400 text-[10px] font-black uppercase tracking-widest italic">
                                                No eligible tickets found for this tier.
                                            </div>
                                        </template>
                                        <template x-for="ticket in previewData.tickets" :key="ticket.id">
                                            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex justify-between items-center transition hover:border-blue-200">
                                                <div>
                                                    <div class="text-xs font-black text-slate-900 italic" x-text="`#${ticket.ticket_number}`"></div>
                                                    <div class="text-[8px] text-slate-400 font-black uppercase" x-text="ticket.user.name"></div>
                                                </div>
                                                <div class="text-[10px] font-black text-emerald-600 italic" x-text="`৳ ${Number(previewData.prize_per_winner).toLocaleString()}`"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <form :action="`/draws/{{ $draw->id }}/confirm/${selectedTier}`" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="prize_per_winner" :value="previewData.prize_per_winner">
                                        <template x-if="selectedTier == 4">
                                            <input type="hidden" name="winning_digit" :value="previewData.digit">
                                        </template>
                                        <template x-for="ticket in previewData.tickets" :key="ticket.id">
                                            <input type="hidden" name="ticket_ids[]" :value="ticket.id">
                                        </template>

                                        <button type="submit" :disabled="previewData.tickets.length === 0 || winners[selectedTier]" 
                                            class="w-full bg-blue-600 text-white text-[11px] font-black py-5 rounded-3xl uppercase tracking-widest shadow-xl shadow-blue-900/20 hover:scale-[1.02] transition-all italic disabled:opacity-50 disabled:scale-100">
                                            Finalize & Credit Balance
                                        </button>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing Winners Summary (Quick Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-10">
                @foreach([1, 2, 3, 4, 5] as $tierId)
                    <div class="bg-white rounded-3xl p-5 border border-white shadow-xl shadow-blue-900/5">
                        <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Tier {{ $tierId }}</div>
                        <div class="flex justify-between items-center">
                            @if(isset($winners[$tierId]))
                                <div class="text-xs font-black text-blue-600 italic">৳ {{ number_format($winners[$tierId]->first()->prize_amount, 0) }}</div>
                                <span class="bg-blue-50 text-blue-600 text-[8px] font-black px-2 py-0.5 rounded-full uppercase border border-blue-100 italic">{{ $winners[$tierId]->count() }}</span>
                            @else
                                <div class="text-xs font-black text-slate-200 italic">Pending</div>
                                <span class="bg-slate-50 text-slate-300 text-[8px] font-black px-2 py-0.5 rounded-full uppercase border border-slate-100 italic">0</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recent Entries Listing (Density Focus) -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white mb-10">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Finalized Winners / <span class="text-blue-600 font-bold italic">Official Selection</span></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Tier</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Ticket #</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Winner Name</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Fulfillment</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Prize Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php $hasAnyWinner = false; @endphp
                            @foreach([1, 2, 3, 4, 5] as $tierId)
                                @if(isset($winners[$tierId]))
                                    @php $hasAnyWinner = true; @endphp
                                    @foreach($winners[$tierId] as $winner)
                                        <tr class="hover:bg-blue-50/30 transition">
                                            <td class="px-8 py-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-blue-600 text-white uppercase tracking-widest italic shadow-sm">Tier {{ $tierId }}</span>
                                            </td>
                                            <td class="px-8 py-4 text-sm font-black text-slate-900 italic tracking-tighter">#{{ $winner->ticket_number }}</td>
                                            <td class="px-8 py-4 text-xs font-black text-slate-900 tracking-tight italic">{{ $winner->user->name }}</td>
                                            <td class="px-8 py-4">
                                                <div class="flex items-center space-x-3">
                                                    @if($winner->status === 'completed')
                                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest italic">✓ GIVEN</span>
                                                    @else
                                                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest italic">⏳ PENDING</span>
                                                        @if(in_array($tierId, [1, 2, 3]))
                                                            <form action="{{ route('tickets.finalize-prize', $winner->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="bg-slate-900 text-white text-[8px] font-black px-3 py-1 rounded uppercase tracking-widest hover:bg-blue-600 transition shadow-md italic">
                                                                    Mark as Given
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-8 py-4 text-right text-[10px] font-black text-emerald-600 italic">৳ {{ number_format($winner->prize_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            @if(!$hasAnyWinner)
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[9px]">No winners selected for this draw yet.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Entries Listing (Density Focus) -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/5 border border-white">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Recent Sales / <span class="text-slate-400 font-bold italic">{{ $draw->tickets()->count() }} total entries</span></h3>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Live Audit Feed</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Entry Code</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">User Profile</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Timestamp</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Prize Hierarchy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($tickets->take(100) as $ticket)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-4 text-sm font-black text-slate-900 italic tracking-tighter">#{{ $ticket->ticket_number }}</td>
                                    <td class="px-8 py-4">
                                        <div class="text-xs font-black text-slate-900 tracking-tight italic">{{ $ticket->user->name }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold italic">{{ $ticket->user->phone }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $ticket->created_at->format('M d, H:i:s') }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        @if($ticket->is_winner)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-blue-50 text-blue-600 uppercase tracking-widest italic border border-blue-100">Tier {{ $ticket->prize_tier_id }} Winner</span>
                                        @else
                                            <span class="text-slate-200 text-[10px] font-black tracking-widest">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>