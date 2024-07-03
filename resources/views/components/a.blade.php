<a {{ $attributes->merge(['class' => 'p-2 md:py-2 md:px-3 inline-block bg-cyan-600 border border-gray-300 rounded-md font-semibold text-sm text-white uppercase shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500  disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
