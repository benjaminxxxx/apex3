<x-app-layout>
    <x-slot name="title">
        Administración de Asociados
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Asociados
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2 md:p-10 mt-2 md:mt-5">
        @livewire("admin-user")
    </div>
</x-app-layout>
