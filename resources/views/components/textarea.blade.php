@props(['disabled' => false,'value'=>null])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-3xl focus:border-gray-400 border border-gray-300 w-full px-6 py-3 text-xs text-gray-600 block focus:ring-0 focus:outline-none focus:shadow-none']) !!}>{{$value??$slot}}</textarea>
