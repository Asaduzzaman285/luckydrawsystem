@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white/5 border-white/10 text-white focus:border-amber-400 focus:ring-amber-400 rounded-xl shadow-sm transition block mt-1 w-full py-3 px-4 placeholder-gray-500']) }}>