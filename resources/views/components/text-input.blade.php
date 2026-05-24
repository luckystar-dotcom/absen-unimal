@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-700 bg-slate-900 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm py-2.5 px-3.5']) }}>
