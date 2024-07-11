<x-app-layout>
    <x-slot name="title">

        @if (Auth::user()->role_id == '2')
            Administración de Gestores
        @elseif (Auth::user()->role_id == '3')
            Administración de Asociados
        @else
            Administración de Asociados
        @endif
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (Auth::user()->role_id == '2')
                Administración de Gestores
            @elseif (Auth::user()->role_id == '3')
                Administración de Asociados
            @else
                Administración de Asociados
            @endif
        </h2>
    </x-slot>

    <livewire:admin-user />
</x-app-layout>
