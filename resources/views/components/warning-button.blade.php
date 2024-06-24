<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-2 py-1  bg-amber-400 border border-amber-500 rounded-md font-semibold text-sm text-gray-900 uppercase tracking-widest shadow-sm hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
