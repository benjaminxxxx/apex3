<button {{ $attributes->merge(['type' => 'submit', 'class' => 'rounded-3xl focus:border-cyan-400 border border-gray-300 px-12 py-3 text-xs text-white mb-6 inline-block focus:ring-0 focus:outline-none focus:shadow-none bg-cyan-700 hover:bg-cyan-800 font-medium']) }}>
    {{ $slot }}
</button>
