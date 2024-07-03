@props(['value'])

<div {{ $attributes->merge(['class' => 'bg-green-600 text-white text-sm p-3 rounded-lg mb-6']) }}>
    {{ $value ?? $slot }}
</div>
