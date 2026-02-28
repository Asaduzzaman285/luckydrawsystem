<x-app-layout>
    <x-slot name="header">
        <div x-data class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition">product</a>
                    <span>/</span>
                    <span class="text-amber-500">inventory</span>
                </nav>
                <h1 class="text-pro-title">asset manifest</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Manage raffle entries, product assets, and participation bundles</p>
            </div>
            <button @click="$dispatch('open-create-modal')" class="btn-pro-primary !bg-maroon !text-white !shadow-maroon-500/20">
                <span class="mr-2 text-lg">+</span> Create New Product
            </button>
        </div>
    </x-slot>

    <div class="pb-24" x-data="{ createModal: false }" @open-create-modal.window="createModal = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Total Products</span>
                    <div class="stats-value text-white">{{ $stats['total_products'] }}</div>
                    <span class="stats-subtext">Items in catalog</span>
                </div>
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Active Manifests</span>
                    <div class="stats-value text-amber-500">{{ $stats['linked_to_draws'] }}</div>
                    <span class="stats-subtext">Executing on terminal</span>
                </div>
                <div class="stats-card stats-card-maroon">
                    <span class="stats-label">Global Valuation</span>
                    <div class="stats-value text-white tracking-tighter"><span class="text-amber-500 mr-1">$</span>{{ number_format($stats['average_price'], 0) }}</div>
                    <span class="stats-subtext">Unit average</span>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-maroon rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 text-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Catalog Item</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Associated Draw</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10">Price / Entries</th>
                                <th class="px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-widest border-b border-white/10 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($products as $product)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-white/5 rounded-xl overflow-hidden border border-white/10 flex-shrink-0">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-white/20 uppercase italic">NA</div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-black text-white tracking-tight">{{ $product->name }}</div>
                                                <div class="text-[10px] text-white/40 font-bold uppercase tracking-widest mt-1">ID: {{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($product->draw)
                                            <div class="text-xs font-black text-white tracking-tighter">{{ $product->draw->title }}</div>
                                            <div class="text-[10px] text-amber-500 font-bold mt-1 uppercase tracking-widest">Live Raffle</div>
                                        @else
                                            <span class="text-xs font-bold text-white/20 italic uppercase">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-white tracking-tighter">৳ {{ number_format($product->price, 2) }}</div>
                                        <div class="text-[10px] text-white/40 font-bold mt-1 tracking-widest uppercase truncate">Individual Ticket Cost</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn-pro-secondary !bg-white/10 !text-white !border-white/10 hover:!bg-white hover:!text-gray-900 transition">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-pro-danger !bg-red-500/10 !text-red-500 !border-red-500/20 hover:!bg-red-500 hover:!text-white transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-white/20 italic font-bold">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/10">
                    {{ $products->links() }}
                </div>
                @endif
            </div>

            <!-- Create Product Modal -->
            <div x-show="createModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-cloak>
                <div class="bg-gray-900 w-full max-w-2xl rounded-[3rem] p-12 shadow-2xl relative border border-white/10">
                    <button @click="createModal = false" class="absolute top-10 right-10 text-white/40 hover:text-white transition font-black text-xl">✕</button>
                    <div class="mb-10 text-center text-white">
                        <h3 class="text-3xl font-black tracking-tighter lowercase italic">catalog / <span class="text-bkash">entry</span></h3>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-2">New raffle asset deployment</p>
                    </div>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Asset Name</label>
                                <input type="text" name="name" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" placeholder="e.g. Diamond Entry" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Assigned Draw</label>
                                @php
                                    $availableDraws = \App\Models\Draw::whereNotIn('status', ['completed', 'cancelled'])->get();
                                @endphp
                                <select name="draw_id" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition text-sm">
                                    @foreach($availableDraws as $draw)
                                        <option value="{{ $draw->id }}" class="bg-gray-900">{{ $draw->title }} ({{ $draw->status }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Price (৳)</label>
                                <input type="number" step="0.01" name="price" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" placeholder="0.00" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Asset Thumbnail</label>
                                <input type="file" name="image" class="w-full bg-white/5 border-white/10 rounded-2xl py-3 px-6 font-bold text-white focus:ring-bkash transition text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-bkash/10 file:text-bkash hover:file:bg-bkash/20">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Description / Spec</label>
                            <textarea name="description" rows="3" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 font-bold text-white focus:ring-bkash transition" placeholder="Details about the raffle prize or entry tier"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-bkash text-white font-black py-5 rounded-2xl shadow-xl shadow-pink-500/20 hover:scale-[1.02] transition uppercase tracking-widest text-xs italic">
                            Commit Asset to Manifest
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
