<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight">
            {{ __('Administrator') }} <span class="text-amber-500">Control Center</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-slate-900">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total System Liquidity
                    </div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">
                        ${{ number_format($stats['total_balance'], 2) }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-amber-400">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Draw Events
                    </div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['active_draws'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-red-400">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Requests</div>
                    <div class="mt-1 text-2xl font-bold text-red-600">{{ $stats['pending_withdrawals'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-blue-400">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Registered Members
                    </div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total_users'] }}</div>
                </div>
            </div>

            <!-- Pending Withdrawals Table -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-100">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-900">Withdrawal <span
                                class="text-amber-500">Approvals</span></h3>
                        <span
                            class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-full uppercase">Queue</span>
                    </div>

                    @if(session('success'))
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-sm">
                            <span class="mr-2">✅</span>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                                    <th class="px-6 py-4 text-left">Member</th>
                                    <th class="px-6 py-4 text-left">Amount</th>
                                    <th class="px-6 py-4 text-left">Reference ID</th>
                                    <th class="px-6 py-4 text-left">Request Date</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($pendingWithdrawals as $tx)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-5 text-slate-900">{{ $tx->user->name }}</td>
                                        <td class="px-6 py-5 text-slate-900 font-bold">${{ number_format($tx->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-5 text-slate-400 font-mono text-sm tracking-tighter">
                                            {{ strtoupper($tx->reference_id) }}
                                        </td>
                                        <td class="px-6 py-5 text-slate-400 text-sm italic">
                                            {{ $tx->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            @can('approve-withdrawal')
                                                <form action="{{ route('withdrawals.approve', $tx->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-6 py-2 bg-slate-900 border border-transparent text-amber-400 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-amber-400 hover:text-slate-900 transition duration-300 shadow-lg active:scale-95">
                                                        Authorize
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-300 text-xs italic font-medium">Restricted</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        {{ $pendingWithdrawals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>