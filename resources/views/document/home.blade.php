<x-app-layout>
    <x-slot name="title">
        Documentos
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <livewire:document-main :document_type="1"/>
        </x-slot>
        <x-slot name="aside">
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
