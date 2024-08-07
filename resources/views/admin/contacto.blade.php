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
                    @if (Auth::user()->role_id == '1')
                        @livewire('admin-contact')
                    @else
                        @livewire('contact-us')
                    @endif
                </x-card>
            </div>
            <div class="w-full lg:w-[400px] lg:pl-3">
                @if (Auth::user()->role_id != '1')
                    <livewire:contact-information />
                @endif
                <livewire:chat />
            </div>
        </div>
    </div>
</x-app-layout>
