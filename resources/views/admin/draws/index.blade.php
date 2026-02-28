<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition">draw</a>
                    <span>/</span>
                    <span class="text-amber-500">management</span>
                </nav>
                <h1 class="text-pro-title">draw infrastructure</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Initialize and monitor active lucky draw event containers</p>
            </div>
            <button @click="$dispatch('open-create-modal')" class="btn-pro-primary !bg-maroon !text-white !shadow-maroon-500/20">
                <span class="mr-2 text-lg">+</span> Create New Draw
            </button>
        </div>
    </x-slot>

    <div class="pb-24" x-data="{ createModal: false }" @open-create-modal.window="createModal = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Total Draws</span>
                    <div class="stats-value text-white">{{ $stats['total_draws'] }}</div>
                    <span class="stats-subtext">{{ $stats['active_this_month'] }} active this month</span>
                </div>
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Live Now</span>
                    <div class="stats-value text-emerald-500">{{ $stats['live_now'] }}</div>
                    <span class="stats-subtext">Draw ends tonight</span>
                </div>
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Active Containers</span>
                    <div class="stats-value text-emerald-500">{{ $stats['live_now'] }}</div>
                    <span class="stats-subtext">Executing protocols</span>
                </div>
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Aggregated Sales</span>
                    <div class="stats-value text-white tracking-tighter"><span class="text-amber-500 mr-1">$</span>{{ number_format($stats['total_revenue'], 0) }}</div>
                    <span class="stats-subtext">Lifetime volume</span>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 text-white">
                <!-- Toolbar -->
                <div class="px-8 py-6 bg-white/5 border-b border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-96">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" placeholder="Search draws by title..." class="w-full bg-white/5 border-transparent rounded-xl py-3 pl-12 pr-4 text-sm font-bold text-white focus:bg-white/10 focus:ring-amber-400 transition-all">
                    </div>
                    <div class="flex items-center space-x-4 w-full md:w-auto">
                        <select class="bg-white/5 border-transparent rounded-xl py-3 px-6 text-[10px] font-black uppercase tracking-widest text-white/40 focus:bg-white/10 focus:ring-amber-400 transition-all w-full md:w-auto">
                            <option class="bg-gray-900">Status: All</option>
                            <option class="bg-gray-900">Live</option>
                            <option class="bg-gray-900">Ended</option>
                        </select>
                        <select class="bg-white/5 border-transparent rounded-xl py-3 px-6 text-[10px] font-black uppercase tracking-widest text-white/40 focus:bg-white/10 focus:ring-amber-400 transition-all w-full md:w-auto">
                            <option class="bg-gray-900">Sort: Draw Time</option>
                            <option class="bg-gray-900">Price: Low to High</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Title</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Tickets Sold</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Draw Time</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($draws as $draw)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-8 py-6">
                                        <div class="font-black text-white tracking-tight">{{ $draw->title }}</div>
                                        <div class="text-[10px] text-white/40 font-bold uppercase tracking-widest mt-1">Draw #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-black text-white tracking-tighter italic">
                                                {{ $draw->sold_tickets }} <span class="text-white/40">/ {{ $draw->max_tickets ?? '∞' }} tickets</span>
                                            </span>
                                        </div>
                                        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-amber-400 h-full" style="width: {{ ($draw->sold_tickets / max(1, $draw->max_tickets ?? 100)) * 100 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="badge-dot {{ $draw->status === 'live' ? 'badge-dot-live' : ($draw->status === 'completed' ? 'badge-dot-ended' : 'badge-dot-upcoming') }}">
                                            <span class="dot {{ $draw->status === 'live' ? 'dot-live' : ($draw->status === 'completed' ? 'dot-ended' : 'dot-upcoming') }}"></span>
                                            {{ ucfirst($draw->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-black text-white tracking-tighter">{{ $draw->draw_time->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-white/40 font-bold mt-1">{{ $draw->draw_time->format('H:i') }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('draws.show', $draw->id) }}" class="btn-pro-secondary !bg-white/10 !text-white !border-white/10 hover:!bg-white hover:!text-gray-900 transition">View</a>
                                            
                                            @if($draw->status !== 'completed')
                                                <form action="{{ route('draws.select-winner', $draw->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn-pro-action !bg-amber-400/10 !text-amber-400 !border-amber-400/20 hover:!bg-amber-400 hover:!text-gray-900 transition" onclick="return confirm('Trigger winner selection for this draw?')">
                                                        <span class="mr-1.5">🏆</span> Pick Winner
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('draws.destroy', $draw->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-pro-danger !bg-red-500/10 !text-red-500 !border-red-500/20 hover:!bg-red-500 hover:!text-white transition" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($draws->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/10">
                    {{ $draws->links() }}
                </div>
                @endif
            </div>

            <!-- Create Draw Modal -->
            <div x-show="createModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
                <div class="bg-gray-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative border border-white/10">
                    <button @click="createModal = false" class="absolute top-10 right-10 text-white/40 hover:text-white transition font-black text-xl">✕</button>
                    <div class="mb-10 text-center text-white">
                        <h3 class="text-3xl font-black tracking-tighter lowercase italic">initialize / <span class="text-bkash">draw</span></h3>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-2">Create a new raffle event container</p>
                    </div>
                    <form action="{{ route('draws.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Draw Title</label>
                            <input type="text" name="title" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" placeholder="e.g. Weekly Grand Raffle" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Max Tickets (Capacity)</label>
                                <input type="number" name="max_tickets" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" placeholder="e.g. 1000">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Start Time</label>
                                <input type="datetime-local" name="start_time" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">End Time</label>
                                <input type="datetime-local" name="end_time" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Draw Time</label>
                                <input type="datetime-local" name="draw_time" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-bkash text-white font-black py-5 rounded-2xl shadow-xl shadow-pink-500/20 hover:scale-[1.02] transition uppercase tracking-widest text-xs italic">
                            Launch Draw Protocol
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>