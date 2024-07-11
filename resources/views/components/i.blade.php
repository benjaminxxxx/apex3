@props(['value'])

<i {{ $attributes->merge(['class' => 'text-cyan-500 mr-4']) }}>
    {{ $value ?? $slot }}
</i>
