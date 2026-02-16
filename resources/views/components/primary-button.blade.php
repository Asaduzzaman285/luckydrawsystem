<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-3 bg-amber-400 border border-transparent rounded-xl font-bold text-sm text-slate-900 uppercase tracking-widest hover:bg-white active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition duration-300 shadow-xl']) }}>
    {{ $slot }}
</button>