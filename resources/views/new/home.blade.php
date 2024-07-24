<x-app-layout>
    <x-slot name="title">
        Noticias
    </x-slot>

    <x-slot name="header">
        
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <x-max-content>
                <x-header>Noticias</x-header>
                <livewire:new-main :news_type="1"/>
            </x-max-content>
        </x-slot>
        <x-slot name="aside">
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
