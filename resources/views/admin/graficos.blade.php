<x-app-layout>
    <x-slot name="title">
        Gráficos
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Gráficos Estadisticos
        </h2>
    </x-slot>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <livewire:chart-group/>
    <livewire:dialog-data-inversion/>
</x-app-layout>
