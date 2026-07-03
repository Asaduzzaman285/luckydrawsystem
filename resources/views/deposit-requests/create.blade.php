<x-app-layout>
    <div class="min-h-screen bg-var(--background) pb-24" x-data="{ amount: 500 }">
        <!-- Header Section -->
        <div class="bg-blue-600 rounded-b-[3rem] p-8 pt-12 shadow-2xl relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center text-white backdrop-blur-sm hover:bg-white/20 transition">
                        <span class="text-lg">←</span>
                    </a>
                    <div>
                        <div class="text-[10px] font-black text-white/60 uppercase tracking-widest italic">Wallet Funding</div>
                        <h1 class="text-2xl font-black text-white tracking-tight italic">Deposit Request</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 md:px-8 max-w-2xl mx-auto">
            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-2xl mb-8">
                <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest italic mb-2">Step 1: Send Money</h3>
                <p class="text-[11px] text-blue-800/80 font-medium leading-relaxed">
                    Please send the desired amount to your assigned agent via bKash or Nagad.
                </p>
                <div class="mt-4 bg-white p-4 rounded-xl shadow-sm border border-blue-100">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Your Assigned Agent</div>
                    <div class="flex justify-between items-center">
                        <div class="text-sm font-black text-slate-900 italic">{{ $agent->name }}</div>
                        <div class="text-xs font-black text-blue-600 italic tracking-tighter">{{ $agent->phone }}</div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('deposit.request.store') }}" method="POST" class="space-y-6 bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-white">
                @csrf
                
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest italic mb-6 border-b border-slate-100 pb-4">Step 2: Submit Details</h3>

                <!-- Amount -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block">Deposit Amount (৳)</label>
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <button type="button" @click="amount = 100" :class="amount == 100 ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="py-3 rounded-xl text-xs font-black italic transition">100</button>
                        <button type="button" @click="amount = 200" :class="amount == 200 ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="py-3 rounded-xl text-xs font-black italic transition">200</button>
                        <button type="button" @click="amount = 500" :class="amount == 500 ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="py-3 rounded-xl text-xs font-black italic transition">500</button>
                        <button type="button" @click="amount = 1000" :class="amount == 1000 ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'" class="py-3 rounded-xl text-xs font-black italic transition">1000</button>
                    </div>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-black italic">৳</span>
                        <input type="number" name="amount" x-model="amount" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 pl-12 pr-6 text-sm font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner" required min="10">
                    </div>
                    @error('amount')
                        <p class="text-[10px] text-red-600 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block">Payment Method Used</label>
                    <select name="payment_method" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner" required>
                        <option value="bKash">bKash</option>
                        <option value="Nagad">Nagad</option>
                    </select>
                    @error('payment_method')
                        <p class="text-[10px] text-red-600 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction ID -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block">Transaction ID (TrxID)</label>
                    <input type="text" name="transaction_id" placeholder="e.g. 9J2A8F3H" value="{{ old('transaction_id') }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-black text-slate-900 focus:ring-blue-600 focus:border-blue-600 transition shadow-inner uppercase placeholder:normal-case" required>
                    <p class="text-[9px] font-medium text-slate-400 italic">Enter the exact Transaction ID you received from bKash/Nagad after sending the money.</p>
                    @error('transaction_id')
                        <p class="text-[10px] text-red-600 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white text-[11px] font-black py-5 rounded-2xl uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-900/20 italic mt-4">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
