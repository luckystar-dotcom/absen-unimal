<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 border border-transparent rounded-md font-bold text-sm text-slate-900 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
