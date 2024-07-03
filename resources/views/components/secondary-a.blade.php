<a {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-1  bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500  disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
