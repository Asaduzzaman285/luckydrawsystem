<x-app-layout>
    <div class="min-h-screen bg-slate-50 pt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-12">
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition">admin</a>
                    <span>/</span>
                    <a href="{{ route('products.index') }}" class="hover:text-amber-500 transition">catalog</a>
                    <span>/</span>
                    <span class="text-amber-500">configure</span>
                </nav>
                <h1 class="text-pro-title !italic !text-5xl uppercase">Configure Asset</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Update parameters for <span class="text-slate-900">{{ $product->name }}</span></p>
            </div>

            <div class="stats-card !p-10 mb-24">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <!-- Draw Selection -->
                    <div class="space-y-2">
                        <label for="draw_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned Draw Container</label>
                        <select name="draw_id" id="draw_id" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition" required>
                            <option value="">Select Protocol Target...</option>
                            @foreach($draws as $draw)
                                <option value="{{ $draw->id }}" {{ (old('draw_id', $product->draw_id) == $draw->id) ? 'selected' : '' }}>
                                    {{ $draw->title }} ({{ strtoupper($draw->status) }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('draw_id')" class="mt-2" />
                    </div>

                    <!-- Product Name -->
                    <div class="space-y-2">
                        <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Nomenclature</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" 
                            class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Pricing Row -->
                    <div class="space-y-2">
                        <label for="price" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Valuation ($)</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" 
                            class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition" required>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Specifications</label>
                        <textarea name="description" id="description" rows="4" 
                            class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition">{{ old('description', $product->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-6">
                        <label for="image" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Visual Asset Protocol</label>
                        
                        @if($product->image)
                            <div class="flex items-center space-x-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-20 w-20 rounded-xl object-cover shadow-sm ring-2 ring-white">
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Asset</div>
                                    <div class="text-xs font-bold text-slate-600 mt-1 italic">Protocol linked successfully</div>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition file:mr-6 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-amber-400 hover:file:text-slate-900 file:transition">
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic uppercase">Encrypted upload protocol active. Max size: 2MB.</p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="pt-8 flex items-center space-x-6">
                        <button type="submit" class="btn-pro-primary !py-4 !px-12 uppercase tracking-[0.2em] text-[10px]">
                            Update Asset Parameters
                        </button>
                        <a href="{{ route('products.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition">Abort Protocol</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
