@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full sm:text-sm border-cyan-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-600']) !!}>
