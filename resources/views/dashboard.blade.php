<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <livewire:chat :popup="true"/>
    <div class="p-2 lg:p-10">
        <div class="grid grid-cols-4 gap-2 md:gap-6">
            <div class="col-span-4 lg:col-span-2">
                <livewire:last-post/>
            </div>
            <div class="col-span-4 md:col-span-2 lg:col-span-1">
                <livewire:latest-news :take="3" :widthImage="true" :withShowMore="false"/>
            </div>
            <div class="col-span-4 md:col-span-2 lg:col-span-1">
                <livewire:calendar/>
            </div>
        </div>
    </div>
</x-app-layout>
