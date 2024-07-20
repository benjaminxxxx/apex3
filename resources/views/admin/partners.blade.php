<x-app-layout>
    <x-slot name="title">
        Administración de Asociados
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Asociados
        </h2>
    </x-slot>

    <livewire:admin-partners />

    <livewire:chat :popup="true"/>
</x-app-layout>
