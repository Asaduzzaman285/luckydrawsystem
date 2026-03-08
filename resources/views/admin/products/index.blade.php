<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">product</a>
                    <span>/</span>
                    <span class="text-blue-600">inventory</span>
                </nav>
                <h1 class="text-pro-title">asset manifest</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Manage raffle entries, product assets, and participation bundles</p>
            </div>
            <button @click="$dispatch('open-create-modal')" class="btn-pro-primary !bg-blue-600 !text-white !shadow-blue-500/20">
                <span class="mr-2 text-lg">+</span> Create New Product
            </button>
        </div>
    </x-slot>

    <div class="pb-24" x-data="{ createModal: false }" @open-create-modal.window="createModal = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Total Products</span>
                    <div class="stats-value text-white">{{ $stats['total_products'] }}</div>
                    <span class="stats-subtext">Items in catalog</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Active Manifests</span>
                    <div class="stats-value text-emerald-400">{{ $stats['linked_to_draws'] }}</div>
                    <span class="stats-subtext">Executing on terminal</span>
                </div>
                <div class="stats-card stats-card-pro">
                    <span class="stats-label">Global Valuation</span>
                    <div class="stats-value text-white tracking-tighter"><span class="text-amber-400 mr-1">৳</span>{{ number_format($stats['average_price'], 0) }}</div>
                    <span class="stats-subtext">Unit average</span>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100 text-slate-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Catalog Item</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Associated Draw</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Price / Entries</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-slate-300 uppercase italic">NA</div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 tracking-tight">{{ $product->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: {{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($product->draw)
                                            <div class="text-xs font-black text-slate-900 tracking-tighter">{{ $product->draw->title }}</div>
                                            <div class="text-[10px] text-blue-600 font-bold mt-1 uppercase tracking-widest">Live Raffle</div>
                                        @else
                                            <span class="text-xs font-bold text-slate-300 italic uppercase">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">৳ {{ number_format($product->price, 2) }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1 tracking-widest uppercase truncate">Individual Ticket Cost</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn-pro-secondary hover:!bg-blue-600 hover:!text-white hover:!border-blue-600 transition">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-pro-danger transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-300 italic font-bold uppercase tracking-widest text-[10px]">No products found in manifest</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                    {{ $products->links() }}
                </div>
                @endif
            </div>

            <!-- Create Product Modal -->
            <div x-show="createModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative border border-white">
                    <button @click="createModal = false" class="absolute top-10 right-10 text-slate-400 hover:text-slate-900 transition font-black text-xl">✕</button>
                    <div class="mb-10 text-center text-slate-900">
                        <h3 class="text-3xl font-black tracking-tighter lowercase italic">catalog / <span class="text-blue-600">entry</span></h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">New raffle asset deployment</p>
                    </div>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Name</label>
                                <input type="text" name="name" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="e.g. Diamond Entry" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned Draw</label>
                                @php
                                    $availableDraws = \App\Models\Draw::whereNotIn('status', ['completed', 'cancelled'])->get();
                                @endphp
                                <select name="draw_id" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition text-sm">
                                    @foreach($availableDraws as $draw)
                                        <option value="{{ $draw->id }}">{{ $draw->title }} ({{ $draw->status }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Price (৳)</label>
                                <input type="number" step="0.01" name="price" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="0.00" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Thumbnail</label>
                                <input type="file" name="image" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-600/10 file:text-blue-600 hover:file:bg-blue-600/20">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description / Spec</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="Details about the raffle prize or entry tier"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 hover:scale-[1.02] transition uppercase tracking-widest text-xs italic">
                            Commit Asset to Manifest
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
