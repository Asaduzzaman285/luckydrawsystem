<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">draw</a>
                    <span>/</span>
                    <span class="text-blue-600">management</span>
                </nav>
                <h1 class="text-pro-title">draw infrastructure</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Initialize and monitor active lucky draw event containers</p>
            </div>
            <button @click="$dispatch('open-create-modal')" class="btn-pro-primary !bg-blue-600 !text-white !shadow-blue-500/20">
                <span class="mr-2 text-lg">+</span> Create New Draw
            </button>
        </div>
    </x-slot>

    <div class="pb-24" x-data="{ createModal: false }" @open-create-modal.window="createModal = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Total Draws</span>
                    <div class="stats-value text-white">{{ $stats['total_draws'] }}</div>
                    <span class="stats-subtext">{{ $stats['active_this_month'] }} active this month</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Live Now</span>
                    <div class="stats-value text-emerald-400">{{ $stats['live_now'] }}</div>
                    <span class="stats-subtext">Draw ends tonight</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Active Containers</span>
                    <div class="stats-value text-emerald-400">{{ $stats['live_now'] }}</div>
                    <span class="stats-subtext">Executing protocols</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Aggregated Sales</span>
                    <div class="stats-value text-white tracking-tighter"><span class="text-amber-400 mr-1">৳</span>{{ number_format($stats['total_revenue'], 0) }}</div>
                    <span class="stats-subtext">Lifetime volume</span>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100 text-slate-900">
                <!-- Toolbar -->
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-96">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" placeholder="Search draws by title..." class="w-full bg-white border-slate-200 rounded-xl py-3 pl-12 pr-4 text-sm font-bold text-slate-900 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="flex items-center space-x-4 w-full md:w-auto">
                        <select class="bg-white border-slate-200 rounded-xl py-3 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400 focus:ring-blue-500 transition-all w-full md:w-auto">
                            <option>Status: All</option>
                            <option>Live</option>
                            <option>Ended</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Title</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tickets Sold</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Draw Time</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($draws as $draw)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="font-black text-slate-900 tracking-tight">{{ $draw->title }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Draw #{{ str_pad($draw->id, 3, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-black text-slate-900 tracking-tighter italic">
                                                {{ $draw->sold_tickets }} <span class="text-slate-400">/ {{ $draw->max_tickets ?? '∞' }} tickets</span>
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-blue-600 h-full shadow-[0_0_8px_rgba(26,86,219,0.4)]" style="width: {{ ($draw->sold_tickets / max(1, $draw->max_tickets ?? 100)) * 100 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="badge-dot {{ $draw->status === 'live' ? 'badge-dot-live' : ($draw->status === 'completed' ? 'badge-dot-ended' : 'badge-dot-upcoming') }}">
                                            <span class="dot {{ $draw->status === 'live' ? 'dot-live' : ($draw->status === 'completed' ? 'dot-ended' : 'dot-upcoming') }}"></span>
                                            {{ ucfirst($draw->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-black text-slate-900 tracking-tighter">{{ $draw->draw_time->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1">{{ $draw->draw_time->format('H:i') }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('draws.show', $draw->id) }}" class="btn-pro-secondary hover:!bg-blue-600 hover:!text-white hover:!border-blue-600 transition">View</a>
                                            
                                            <form action="{{ route('draws.destroy', $draw->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-pro-danger transition" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($draws->hasPages())
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                    {{ $draws->links() }}
                </div>
                @endif
            </div>

            <!-- Create Draw Modal -->
            <div x-show="createModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative border border-white">
                    <button @click="createModal = false" class="absolute top-10 right-10 text-slate-400 hover:text-slate-900 transition font-black text-xl">✕</button>
                    <div class="mb-10 text-center text-slate-900">
                        <h3 class="text-3xl font-black tracking-tighter lowercase italic">initialize / <span class="text-blue-600">draw</span></h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Create a new raffle event container</p>
                    </div>
                    <form action="{{ route('draws.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Draw Title</label>
                            <input type="text" name="title" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="e.g. Weekly Grand Raffle" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Max Tickets (Capacity)</label>
                                <input type="number" name="max_tickets" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="e.g. 1000">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Start Time</label>
                                <input type="datetime-local" name="start_time" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">End Time</label>
                                <input type="datetime-local" name="end_time" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Draw Time</label>
                                <input type="datetime-local" name="draw_time" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 hover:scale-[1.02] transition uppercase tracking-widest text-xs italic">
                            Launch Draw Protocol
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>