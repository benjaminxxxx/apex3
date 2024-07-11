<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-3xl focus:border-green-400 border border-gray-300 px-4 py-3 text-xs text-white inline-block focus:ring-0 focus:outline-none focus:shadow-none bg-green-600 hover:bg-green-700 font-medium']) }}>
    {{ $slot }}
</button>
