<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-3xl focus:border-yellow-400 border border-gray-300 px-4 py-3 text-xs text-white inline-block focus:ring-0 focus:outline-none focus:shadow-none bg-yellow-500 hover:bg-yellow-600 font-medium disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
