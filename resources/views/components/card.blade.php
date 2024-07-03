@props(['value'])

<div  {{ $attributes->merge(['class' => 'shadow border rounded-2xl mb-2 p-6 md:p-6 lg:p-10 bg-white']) }}>
    {{ $value ?? $slot }}
</div>
