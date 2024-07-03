<x-app-layout>
    <x-slot name="title">
        Contacto
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <div class="md:p-10">
        <div class="lg:flex">
            <div class="flex-1 justify-center ">
                <h2 class="font-bold text-3xl text-gray-800 leading-tight mb-2 md:mb-10">
                    Contacto
                </h2>

                <x-card class="w-full">
                    @livewire('admin-contact')
                </x-card>
            </div>
            <div class="w-full lg:w-[400px] lg:pl-3">
                <x-card>
                    @livewire('admin-active-users')
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
