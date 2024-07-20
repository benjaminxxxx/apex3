<x-app-layout>
    <x-slot name="title">
        Grupos
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <livewire:project-home/>
        </x-slot>
        <x-slot name="aside">
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
