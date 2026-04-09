<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24">
        
        <!-- Professional Header -->
        <div class="bg-gradient-to-r from-[#1a56db] to-[#1e3a8a] pt-8 pb-20 px-4 sm:px-8 shadow-inner">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl border-2 border-white/20 overflow-hidden bg-white/10 flex items-center justify-center text-white font-black text-xl italic shadow-lg">
                            A
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-white/60 uppercase tracking-widest">system admin</div>
                            <div class="text-lg font-black text-white tracking-tight leading-none">Control Center</div>
                        </div>
                    </div>
                    
                    <!-- System Stats Pill -->
                    <div class="bg-white/10 backdrop-blur-md rounded-full px-4 py-2 flex items-center space-x-3 border border-white/10 shadow-lg">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest italic">System Live</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Service Grid ("Box Box") -->
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 mb-8 border border-white">
                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-6">
                    <a href="{{ route('draws.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm border border-blue-100/50">🗓️</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-blue-600 leading-tight">manage draws</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-amber-400 transition-all duration-300 shadow-sm border border-amber-100/50">🎁</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-amber-500 leading-tight">products</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300 shadow-sm border border-indigo-100/50">👥</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-indigo-600 leading-tight">users</span>
                    </a>
                    <a href="{{ route('admin.withdrawals.index') }}" class="flex flex-col items-center group text-center relative">
                        @if($stats['pending_withdrawals'] > 0)
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] font-black z-10 shadow-lg">{{ $stats['pending_withdrawals'] }}</div>
                        @endif
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-xl sm:text-2xl group-hover:border-blue-600 border-transparent border-2 transition-all duration-300 shadow-sm">💰</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-900 uppercase tracking-widest mt-2 leading-tight">payouts</span>
                    </a>
                    <a href="{{ route('admin.reports.agents') }}" class="flex flex-col items-center group text-center">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-xl sm:text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm border border-emerald-100/50">📊</div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 uppercase tracking-widest mt-2 group-hover:text-emerald-600 leading-tight">agent reports</span>
                    </a>
                </div>
            </div>

            <!-- Dashboard Stats Hero -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-white shadow-xl shadow-blue-900/5">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Agent Funds Dispatched</div>
                    <div class="text-2xl font-black text-slate-900 tracking-tighter italic">৳ {{ number_format($stats['agent_funds'], 2) }}</div>
                    <div class="h-1 w-10 bg-blue-600 mt-3 rounded-full"></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-white shadow-xl shadow-blue-900/5">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Agents</div>
                    <div class="text-2xl font-black text-indigo-500 tracking-tighter italic">{{ $stats['total_agents'] }} <span class="text-xs text-slate-400 font-bold not-italic ml-1">Registered</span></div>
                    <div class="h-1 w-10 bg-indigo-500 mt-3 rounded-full"></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-white shadow-xl shadow-blue-900/5">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Products</div>
                    <div class="text-2xl font-black text-amber-500 tracking-tighter italic">{{ $stats['total_products'] }} <span class="text-xs text-slate-400 font-bold not-italic ml-1">Items</span></div>
                    <div class="h-1 w-10 bg-amber-400 mt-3 rounded-full"></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-white shadow-xl shadow-blue-900/5">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Winners</div>
                    <div class="text-2xl font-black text-emerald-500 tracking-tighter italic">{{ $stats['total_winners'] }} <span class="text-xs text-slate-400 font-bold not-italic ml-1">Results</span></div>
                    <div class="h-1 w-10 bg-emerald-500 mt-3 rounded-full"></div>
                </div>
            </div>

            <!-- Operational Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Agents By District Chart -->
                <div class="bg-white p-8 rounded-[2rem] border border-white shadow-xl shadow-blue-900/5">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic mb-6">Agents Distribution / <span class="text-slate-400 italic font-bold">By District</span></h3>
                    <div class="h-[250px]">
                        <canvas id="agentsDistrictChart"></canvas>
                    </div>
                </div>

                <!-- Operations Summary Chart -->
                <div class="bg-white p-8 rounded-[2rem] border border-white shadow-xl shadow-blue-900/5">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic mb-6">Operations Glance / <span class="text-slate-400 italic font-bold">Overall Stats</span></h3>
                    <div class="h-[250px]">
                        <canvas id="operationsSummaryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Authorization Queue Table -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-blue-900/10 border border-white">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Pending / <span class="text-slate-400 italic font-bold">Withdrawals</span></h3>
                    <a href="{{ route('admin.withdrawals.index') }}" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline">View All Requests</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Member</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Amount</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Reference</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Administrative Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pendingWithdrawals as $tx)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[10px] font-black text-blue-600 border border-blue-100 uppercase italic">
                                                {{ substr($tx->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 text-xs tracking-tight">{{ $tx->user->name }}</div>
                                                <div class="text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ $tx->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">৳ {{ number_format($tx->amount, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-[10px] text-slate-400 font-mono tracking-widest uppercase truncate max-w-[150px]">{{ $tx->reference_id }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <form action="{{ route('withdrawals.approve', $tx->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 text-white text-[9px] font-black px-5 py-2 rounded-xl uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-105 transition-all outline-none">
                                                Authorize
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-200 italic font-bold uppercase tracking-widest text-[9px]">The Authorization Queue is empty</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Agents By District Chart
            const districtCtx = document.getElementById('agentsDistrictChart').getContext('2d');
            new Chart(districtCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($agentsByDistrict->pluck('name')) !!},
                    datasets: [{
                        label: 'Total Agents',
                        data: {!! json_encode($agentsByDistrict->pluck('agent_count')) !!},
                        backgroundColor: '#1a56db',
                        borderRadius: 8,
                        barThickness: 30,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        }
                    }
                }
            });

            // Operations Summary Chart
            const opsCtx = document.getElementById('operationsSummaryChart').getContext('2d');
            new Chart(opsCtx, {
                type: 'bar',
                data: {
                    labels: ['Draws', 'Products', 'Withdrawals'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            {{ $operationsSummary['draws'] }},
                            {{ $operationsSummary['products'] }},
                            {{ $operationsSummary['withdrawals'] }}
                        ],
                        backgroundColor: [
                            '#1a56db', // Blue
                            '#f59e0b', // Amber
                            '#1e3a8a'  // Dark Blue
                        ],
                        borderRadius: 8,
                        barThickness: 50,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>