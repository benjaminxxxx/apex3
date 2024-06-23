@props(['active' => false, 'menu' => '', 'icon' => '', 'hassubmenu' => false])

@php
$classes = $active
           ? 'flex items-center p-2 text-indigo-900 hover:text-indigo-700 rounded-lg'
           : 'flex items-center p-2 text-gray-900 hover:text-indigo-700 rounded-lg';
@endphp

<li x-data="{ open: false }">
    <a @if($hassubmenu) @click.prevent="open = !open" @else href="{{ $attributes->get('href', '#') }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i class="{{ $icon }} bg-white rounded-md p-2"></i>
        @endif
        @if ($menu)
            <span class="flex-1 ms-3 whitespace-nowrap text-sm">{{ $menu }}</span>
        @endif
    </a>
    @if ($hassubmenu)
        <ul x-show="open" @click.away="open = false" class="ml-4 mt-2 space-y-2">
            {{ $slot }}
        </ul>
    @endif
</li>
