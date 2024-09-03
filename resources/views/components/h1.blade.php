@props(['value'])

<h1  {{ $attributes->merge(['class' => 'text-xl font-bold leading-tight text-center tracking-tight md:text-4xl md:mb-5']) }}>
    {{ $value ?? $slot }}
</h1>
