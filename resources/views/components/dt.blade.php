@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm/[17px] text-gray-700 mt-3 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
