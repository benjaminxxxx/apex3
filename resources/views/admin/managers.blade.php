<x-app-layout>
    <x-slot name="title">
        Administración de Gestores
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Gestores
        </h2>
    </x-slot>

    <livewire:admin-managers />

    <livewire:user-general-options />

    <livewire:chat :popup="true"/>
</x-app-layout>
