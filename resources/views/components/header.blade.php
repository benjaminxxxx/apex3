@props(['value'])

<div  {{ $attributes->merge(['class' => 'py-1 md:py-2 lg:my-2 font-bold text-md']) }}>
    {{ $value ?? $slot }}
</div>
