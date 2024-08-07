<x-app-layout>
    <x-slot name="title">
        Documentos
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <x-max-content>
                <livewire:document-main :document_type="1"/>
            </x-max-content>
        </x-slot>
        <x-slot name="aside">
            <livewire:my-documents/>
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
