<x-app-layout>
    <x-slot name="title">
        Nueva publicación
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Publicaciones
        </h2>
    </x-slot>
    <livewire:admin-post :type="$type" />
</x-app-layout>
