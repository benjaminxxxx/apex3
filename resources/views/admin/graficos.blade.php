<x-app-layout>
    <x-slot name="title">
        Gráficos
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Gráficos Estadisticos
        </h2>
    </x-slot>
    <livewire:chat :popup="true"/>
    <livewire:chart-group/>
</x-app-layout>
