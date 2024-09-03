@props(['value'])

<div  {{ $attributes->merge(['class' => 'shadow border rounded-2xl mb-2 p-2 md:p-4 lg:p-5 2xl:p-10 bg-white']) }}>
    {{ $value ?? $slot }}
</div>
