<button {{ $attributes->merge(['type' => 'button', 'class' => 'justify-center inline-flex items-center px-3 py-2 bg-white border border-cyan-700 rounded-md text-sm text-cyan-700 hover:bg-cyan-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyan-700 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
