@props(['value'])

<h2  {{ $attributes->merge(['class' => 'font-bold text-2xl mb-2']) }}>
    {{ $value ?? $slot }}
</h2>
