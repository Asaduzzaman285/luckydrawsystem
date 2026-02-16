<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight">
            {{ __('Member') }} <span class="text-amber-500">Dashboard</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-600 font-bold flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-600 font-bold flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Wallet Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-xl border-l-4 border-amber-400 transition hover:scale-105 duration-300">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Available Funds</div>
                    <div class="text-3xl font-bold text-slate-900">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border-l-4 border-slate-900 opacity-90">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Deposits</div>
                    <div class="text-xl font-bold text-slate-700">${{ number_format($wallet->lifetime_deposit ?? 0, 2) }}</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border-l-4 border-slate-300 opacity-70">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Payouts</div>
                    <div class="text-xl font-bold text-slate-700">${{ number_format($wallet->lifetime_withdrawal ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- Live Draws Section -->
            <div class="mb-12">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center animate-pulse">
                        <span class="text-slate-900 font-bold">!</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900">Active <span class="text-amber-500">Opportunities</span></h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($liveDraws as $draw)
                        <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-white/5 group hover:border-amber-400/30 transition duration-500">
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-6">
                                    <h4 class="text-xl font-bold text-white group-hover:text-amber-400 transition">{{ $draw->title }}</h4>
                                    <span class="px-3 py-1 bg-amber-400 text-slate-900 text-[10px] font-black rounded-lg uppercase shadow-lg">LIVE</span>
                                </div>
                                <div class="space-y-4 mb-8">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400 font-medium">Ticket Price</span>
                                        <span class="text-white font-bold text-lg">${{ number_format($draw->ticket_price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400 font-medium">Available Slots</span>
                                        <span class="text-white font-medium">{{ $draw->max_tickets ? ($draw->max_tickets - $draw->sold_tickets) . '/' . $draw->max_tickets : 'Unlimited' }}</span>
                                    </div>
                                </div>
                                <form action="{{ route('draws.buy', $draw) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-4 bg-amber-400 hover:bg-white text-slate-900 font-black rounded-2xl shadow-[0_10px_20px_rgba(251,191,36,0.2)] hover:shadow-none transition duration-300 transform active:scale-95 text-sm tracking-widest uppercase">
                                        GET YOUR TICKET
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white p-12 rounded-3xl text-center border-2 border-dashed border-slate-100 italic text-slate-400 font-bold">
                            No active draws at the moment. Please check back soon!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-100">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-900">My Participation <span class="text-amber-500">History</span></h3>
                        <span class="px-4 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full uppercase">Recent Tickets</span>
                    </div>
                    
                    @if($tickets->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-4xl mb-4">🎟️</div>
                            <p class="text-slate-400 font-medium italic">No active draw participations found. Start your luck today!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                                        <th class="px-6 py-4 text-left">Event Title</th>
                                        <th class="px-6 py-4 text-left">Ticket Reference</th>
                                        <th class="px-6 py-4 text-left">Outcome</th>
                                        <th class="px-6 py-4 text-left">Purchase Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-slate-50 transition duration-150">
                                            <td class="px-6 py-5 font-semibold text-slate-700">{{ $ticket->draw->title }}</td>
                                            <td class="px-6 py-5 font-mono text-slate-500 text-sm">{{ strtoupper($ticket->ticket_number) }}</td>
                                            <td class="px-6 py-5">
                                                @if($ticket->is_winner)
                                                    <span class="px-3 py-1 bg-green-500 text-white text-[10px] font-black rounded-lg shadow-sm">WINNER</span>
                                                @else
                                                    <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-bold rounded-lg uppercase">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 text-sm text-slate-400 font-medium">{{ $ticket->created_at->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
