<x-app-layout>
    <x-slot name="title">
        Seleccionar Grupo
    </x-slot>

    <x-slot name="header">
        Seleccionar Grupo
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">
            @foreach ($groups as $group)
                <a href="{{ route('group.go', ['slug' => $group->slug]) }}"
                    class="block p-4 lg:p-10 mb-5 max-w-sm bg-white rounded-2xl border border-cyan-200 shadow-lg text-gray-700 hover:bg-cyan-600 hover:text-white">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight">{{ $group->name }}</h5>
                    <p class="font-normal" >{{ $group->description }}</p>
                </a>
            @endforeach
        </x-slot>
        <x-slot name="aside">
            <livewire:chat />
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
