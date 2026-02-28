<x-app-layout>
    <div class="min-h-screen bg-slate-50 pt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-12">
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition">admin</a>
                    <span>/</span>
                    <a href="{{ route('users.index') }}" class="hover:text-amber-500 transition">registry</a>
                    <span>/</span>
                    <span class="text-amber-500">configure</span>
                </nav>
                <h1 class="text-pro-title !italic !text-5xl uppercase">Configure Identity</h1>
                <p class="text-sm font-bold text-slate-400 mt-2">Adjust personnel parameters and access protocols for <span class="text-slate-900">{{ $user->name }}</span></p>
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

                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Identity</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned District</label>
                            <select name="district_id" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition text-sm">
                                <option value="">Global / Unassigned</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Status</label>
                            <select name="is_active" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold text-slate-900 focus:ring-amber-400 transition text-sm" required>
                                <option value="1" {{ old('is_active', $user->is_active ?? true) ? 'selected' : '' }}>Institutional Access Active</option>
                                <option value="0" {{ !(old('is_active', $user->is_active ?? true)) ? 'selected' : '' }}>Access Terminated / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-8 flex items-center space-x-6">
                        <button type="submit" class="btn-pro-primary !py-4 !px-12 uppercase tracking-[0.2em] text-[10px]">
                            Update Identity Parameters
                        </button>
                        <a href="{{ route('users.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition">Abort Protocol</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
