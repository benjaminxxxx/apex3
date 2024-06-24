@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md']) !!}>
{{$slot}}
</select>
