<div x-data="{ activeTab: 'inicio' }">
    <x-pop class="bg-gray-100">
        <!-- Portada con foto de perfil -->
        <div class="relative">
            @error('cover_image')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            
            <div wire:loading wire:target="cover_image" class="absolute text-xs left-0 top-0 ml-2 mt-2 text-white">Cargando...</div>
            <div class="h-64 bg-cover bg-center" style="background-image: url('{{ $project->cover_image_url }}');">
                @if(Auth::user()->hasPermission('add_projects'))
                <x-icon-edit class="top-0 right-0 mt-4 mr-4 cursor-pointer" @click="$refs.fileInput.click()" />
                <input type="file" x-ref="fileInput" wire:model="cover_image" class="hidden"  accept=".jpg,.jpeg,.png">
                @endif
            </div>

        </div>
        <!-- Contenido del perfil -->
        <div class="">
            @error('profile_image')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            <div class="flex items-center mb-4 p-2 lg:p-10">
                
                <div class="-mt-16 w-32 h-32 overflow-hidden z-50 relative">
                    @if(Auth::user()->hasPermission('add_projects'))
                    <div wire:loading wire:target="profile_image" class="absolute text-xs left-0 top-0 ml-1 mt-1 text-white">Cargando...</div>
                    <x-icon-edit class="bottom-0 right-0 mr-2 mb-2" @click="$refs.fileInput_profile.click()" />
                    @endif
                    <img class="w-full h-full object-cover"  src="{{ $project->profile_image_url }}" alt="{{ $project->name }}">
                    <input type="file" x-ref="fileInput_profile" wire:model="profile_image" class="hidden"  accept=".jpg,.jpeg,.png">
                </div>
                
                <div class="ml-4">
                    <x-h3 class="text-3xl font-bold">{{ $project->name }}</x-h3>
                    <x-label class="">{{ $project->description }}</x-label>
                </div>
            </div>
            <!-- Barra de navegación -->
            <div class="bg-gray-100 p-2 lg:px-10 py-4">
                <ul class="flex space-x-4">
                    <li>
                        <a href="#" @click.prevent="activeTab = 'inicio'"
                           class="text-gray-800 hover:text-orange-600 text-xs cursor-pointer"
                           :class="{ 'text-orange-600': activeTab === 'inicio' }">Inicio</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="activeTab = 'documentos'"
                           class="text-gray-800 hover:text-orange-600 text-xs cursor-pointer"
                           :class="{ 'text-orange-600': activeTab === 'documentos' }">Documentos</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="activeTab = 'grupos'"
                           class="text-gray-800 hover:text-orange-600 text-xs cursor-pointer"
                           :class="{ 'text-orange-600': activeTab === 'grupos' }">Grupos</a>
                    </li>
                    @if(Auth::user()->hasPermission('add_projects'))
                    <li>
                        <a href="#" @click.prevent="activeTab = 'configuracion'"
                           class="text-gray-800 hover:text-orange-600 text-xs cursor-pointer"
                           :class="{ 'text-orange-600': activeTab === 'configuracion' }">Configuración</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </x-pop>
    <div x-show="activeTab === 'inicio'" class="mt-4" id="panel_inicio">
        Contenido del panel de Inicio
    </div>

    <div x-show="activeTab === 'documentos'" class="mt-4" id="panel_documentos">
        <livewire:document-main :document_type="2" :document_project="$project->id"/>
    </div>
    <div x-show="activeTab === 'grupos'" class="mt-4" id="panel_grupos">
        <livewire:groups :project_id="$project->id"/>
    </div>
    <div x-show="activeTab === 'miembros'" class="mt-4" id="panel_miembros">
       
    </div>

    <div x-show="activeTab === 'configuracion'" class="mt-4" id="panel_configuracion">
        <x-form-section submit="updateGroupData">
            <x-slot name="title">
                Información Básica
            </x-slot>
        
            <x-slot name="description">
                Configura la información básica de este grupo
            </x-slot>
        
            <x-slot name="form">
                <div class="col-span-6">
                    <x-label for="name" value="Nombre del grupo" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" autocomplete="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-6">
                    <x-label for="description" value="Descripción" />
                    <x-textarea rows="4" id="description" type="text" class="mt-1 block w-full" wire:model="description" autocomplete="description" />
                    <x-input-error for="description" class="mt-2" />
                </div>
        
            </x-slot>
        
            <x-slot name="actions">
                <x-action-message class="me-3" on="saved">Datos actualizados</x-action-message>
                <x-button wire:loading.attr="disabled" wire:target="photo">Guardar</x-button>
            </x-slot>
        </x-form-section>
    </div>
</div>
