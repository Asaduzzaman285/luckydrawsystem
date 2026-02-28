<x-app-layout>
    <div class="min-h-screen bg-slate-50 pt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-12">
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition">admin</a>
                    <span>/</span>
                    <a href="{{ route('draws.index') }}" class="hover:text-amber-500 transition">draws</a>
                    <span>/</span>
                    <span class="text-amber-500">create</span>
                </nav>
                <h1 class="text-pro-title !italic !text-5xl uppercase">Add Draw</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Initialize a new opportunity for users</p>
            </div>

            <div class="stats-card !p-10 mb-24">
                @if ($errors->any())
                    <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-xl">
                        <div class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-3 italic">Validation Protocol Failed</div>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs font-bold text-red-700 list-disc list-inside uppercase tracking-tight">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('draws.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-full space-y-2">
                            <label for="title" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Event Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition"
                                placeholder="e.g. Dream Car Raffle 2024" required>
                        </div>

                        <div class="space-y-2">
                            <label for="max_tickets" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Max Capacity (Optional)</label>
                            <input type="number" name="max_tickets" id="max_tickets" value="{{ old('max_tickets') }}"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition"
                                placeholder="Unlimited">
                        </div>



                        <div class="space-y-2">
                            <label for="start_time" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Live Window Commencement</label>
                            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label for="end_time" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Live Window Termination</label>
                            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label for="draw_time" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Result Execution Time</label>
                            <input type="datetime-local" name="draw_time" id="draw_time" value="{{ old('draw_time') }}"
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition"
                                required>
                        </div>
                    </div>

                    <div class="pt-8 flex items-center space-x-6">
                        <button type="submit" class="btn-pro-primary !py-4 !px-12 uppercase tracking-[0.2em] text-[10px]">
                            Save Draw
                        </button>
                        <a href="{{ route('draws.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>