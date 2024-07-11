<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-3xl focus:border-gray-400 border border-gray-300 px-4 py-3 text-xs text-gray-800 inline-block focus:ring-0 focus:outline-none focus:shadow-none bg-white hover:bg-gray-200 font-medium disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
