<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-3xl focus:border-red-400 border border-gray-300 px-4 py-3 text-xs text-white inline-block focus:ring-0 focus:outline-none focus:shadow-none bg-red-600 hover:bg-red-700 font-medium']) }}>
    {{ $slot }}
</button>
