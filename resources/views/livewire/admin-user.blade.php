<div>
    <livewire:chat :popup="true"/>
    <x-card>
        <x-h2>
            <label for="activate_grouping" class="flex items-center">
                <x-checkbox id="activate_grouping" name="activate_grouping" wire:model="activate_grouping" wire:change="activating_grouping" />
                <span class="ms-2 text-sm">Agrupar por proyectos</span>
            </label>
        </x-h2>
    </x-card>
    @if($activate_grouping)
    @if($projects->count()>0)
    <div x-data="{ openTab: {{ $projects[0]->id }} }"> 
        @foreach ($projects as $project)
            <x-pop class="mt-10">
                <button class="w-full text-left p-4 focus:outline-none"
                        x-bind:class="{ 'bg-cyan-700 text-white': openTab === {{ $project->id }} }"
                        x-on:click="openTab === {{ $project->id }} ? openTab = null : openTab = {{ $project->id }}">
                    Proyecto: {{ $project->name }}
                </button>
                <div x-show="openTab === {{ $project->id }}" class="p-4">
                    <livewire:users-by-project :projectId="$project->id"/>
                </div>
            </x-pop>
        @endforeach
    </div>
    @else
    <x-card>
        <x-label>No hay proyectos creados aún</x-label>
    </x-card>
    @endif
    @else
    <livewire:user-list/>
    @endif
    
    
    
    
</div>
