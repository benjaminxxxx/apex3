@props(['value'])

<div {{ $attributes->merge(['class' => 'absolute']) }}>
    <svg xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6 text-gray-300 hover:text-gray-700 cursor-pointer" viewBox="0 0 20 20"
        fill="currentColor">
        <path fill-rule="evenodd"
            d="M12.707 3.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.32.213l-3 1a1 1 0 0 1-1.265-1.265l1-3c.04-.12.094-.228.162-.32l9-9zM15 5l-1-1 2-2 1 1-2 2z"
            clip-rule="evenodd" />
    </svg>
    {{ $value ?? $slot }}
</div>