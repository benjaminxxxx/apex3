@props(['value'])

<div  {{ $attributes->merge(['class' => 'shadow border rounded-2xl mb-2 overflow-hidden bg-white']) }}>
    {{ $value ?? $slot }}
</div>
