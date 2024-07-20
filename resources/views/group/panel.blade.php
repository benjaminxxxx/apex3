<x-app-layout>
    <x-slot name="title">
        Grupos
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            <x-max-content>
                <livewire:group-panel :group_id="$group_id"/>
            </x-max-content>
        </x-slot>
        <x-slot name="aside">
            <livewire:chat/>
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
