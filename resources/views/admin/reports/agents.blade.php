<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24">
        
        <!-- Professional Header -->
        <div class="bg-gradient-to-r from-[#1a56db] to-[#1e3a8a] pt-8 pb-20 px-4 sm:px-8 shadow-inner text-white">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-4xl font-black tracking-tighter lowercase italic">agent / <span class="text-blue-200">performance</span></h1>
                <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.3em] mt-3 italic">Administrative Intelligence & Network Analytics</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 -mt-12">
            
            <!-- Filters Card -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl shadow-blue-900/10 border border-white mb-8">
                <form method="GET" action="{{ route('admin.reports.agents') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Scope: District</label>
                        <select name="district_id" class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-900 focus:ring-blue-600 transition" onchange="this.form.submit()">
                            <option value="">All Regions</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Scope: Upazilla</label>
                        <select name="upazilla_id" class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-900 focus:ring-blue-600 transition" onchange="this.form.submit()">
                            <option value="">All Locations</option>
                            @foreach($upazillas as $upazilla)
                                <option value="{{ $upazilla->id }}" {{ request('upazilla_id') == $upazilla->id ? 'selected' : '' }}>{{ $upazilla->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Search: Phone Number</label>
                        <input type="text" name="phone" value="{{ request('phone') }}" placeholder="01XXX-XXXXXX" class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 text-xs font-bold text-slate-900 focus:ring-blue-600 transition">
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white font-black py-3 rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition uppercase tracking-widest text-[10px] italic">Filter Feed</button>
                        <a href="{{ route('admin.reports.agents') }}" class="px-4 py-3 bg-slate-100 text-slate-400 rounded-xl hover:bg-slate-200 transition text-[10px] flex items-center justify-center font-black">✕</a>
                    </div>
                </form>
            </div>

            <!-- Performance Table -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl shadow-blue-900/10 border border-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Identity / Profile</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Regional Scope</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Fund flow</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Reward (10%)</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Market Impact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($agents as $agent)
                                <tr class="hover:bg-blue-50/20 transition duration-300">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xs italic shadow-lg">
                                                {{ substr($agent->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-slate-900 tracking-tighter italic lowercase">{{ $agent->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold tracking-widest">{{ $agent->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $agent->district->name ?? 'N/A' }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase italic">{{ $agent->upazilla->name ?? 'Global' }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-sm font-black text-blue-600 tracking-tighter italic">৳ {{ number_format($agent->total_deposits, 2) }}</div>
                                        <div class="text-[8px] text-slate-300 font-bold uppercase tracking-widest">Dispatched Funds</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="text-sm font-black text-emerald-600 tracking-tighter italic">৳ {{ number_format($agent->total_commissions, 2) }}</div>
                                        <div class="text-[8px] text-slate-300 font-bold uppercase tracking-widest">Lifetime Earnings</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">{{ $agent->tickets_sold }}</div>
                                        <div class="text-[8px] text-slate-400 font-black uppercase tracking-widest">Tickets Sold</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-200 italic font-black uppercase tracking-[0.4em] text-[10px]">No operational data matches these filters</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
