@props(['options' => "{enableTime: true,dateFormat:'Y-m-d H:i'}"])

<div wire:ignore>
    <input
        x-data
        x-init="flatpickr($refs.input, {{ $options }} );"
        x-ref="input"
        type="text"
        data-input
        {{ $attributes->merge(['class' => 'rounded-3xl focus:border-gray-400 border border-gray-300 w-full px-6 py-3 text-xs text-gray-600 block focus:ring-0 focus:outline-none focus:shadow-none']) }}
    />
</div>