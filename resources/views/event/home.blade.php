<x-app-layout>
    <x-slot name="title">
        Eventos
    </x-slot>

    <x-slot name="header">
        
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <x-max-content>
                <x-header>Eventos</x-header>
                <livewire:event-main :event_type="1"/>
            </x-max-content>
        </x-slot>
        <x-slot name="aside">
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
