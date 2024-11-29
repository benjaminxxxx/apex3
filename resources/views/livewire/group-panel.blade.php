<div x-data="{ activeTab: 'inicio' }">
    <x-pop class="bg-gray-100">

        <!-- Contenido del perfil -->
        <div class="">

            <div class="p-6 md:p-10">
                <x-h3 class="text-3xl font-bold">{{ $group->name }}</x-h3>
                <x-label class="">{{ $group->description }}</x-label>
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
                        <a href="#" @click.prevent="activeTab = 'socios'"
                            class="text-gray-800 hover:text-orange-600 text-xs cursor-pointer"
                            :class="{ 'text-orange-600': activeTab === 'socios' }">Socios</a>
                    </li>
                    @if (Auth::user()->hasPermission('add_group'))
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
        @if ($inversiones && $inversiones->count() > 0)
            @foreach ($inversiones as $inversion)
                <x-card class="max-w-3xl mx-auto mt-2 relative">
                    <div class="flex items-start">
                        <img class="w-12 h-12 rounded-full mr-4" src="{{ $inversion->inversor->profile_photo_url }}"
                            alt="{{ $inversion->inversor->fullName }}">
                        <div>
                            <!-- User Name and Time -->
                            <div class="text-lg font-semibold text-gray-800">{{ $inversion->inversor->fullName }}</div>
                            <div class="text-sm text-gray-500">{{ $inversion->created_at_human }}</div>
                        </div>
                        <!-- Document Type Icon -->
                        <div class="ml-auto">
                            Socio Inversionista
                        </div>
                    </div>
                    <!-- Document Title -->
                    <div class="mt-4">
                        <!-- Document Description -->
                        <div class="text-gray-600 mt-2">
                            @php
                                $title="Inversion en Grupo";
                                $chart_type="1";
                                $type="bar";
                                $showlabels="1";
                                $showlegend = '1';
                                
                            @endphp
                            <x-chart id="chart-{{ $inversion->id }}" :title="$title" :charttype="$chart_type" :type="$type"
                                :data="$inversion->chart_data" :showlabels="$showlabels" :showlegend="$showlegend" />

                            <x-table class="mt-8">
                                <x-slot name="thead">
                                    <x-tr>
                                        <x-th>Resumen de la Inversión</x-th>
                                        <x-th>Valor</x-th>
                                    </x-tr>
                                </x-slot>
                                <x-slot name="tbody">
                                    <x-tr>
                                        <x-td>Capital Inicial</x-td>
                                        <x-td>{{ $inversion->capital_invertido }}</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Tipo de Interés</x-td>
                                        <x-td>{{ $inversion->tipo_interes == 'compuesto' ? 'Compuesto' : 'Simple' }}</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Tasa de Interés</x-td>
                                        <x-td>{{ $inversion->tasa_interes_anual }}%</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Plazo</x-td>
                                        <x-td>{{ $inversion->plazo }} meses</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Frecuencia de Pago</x-td>
                                        <x-td>{{ $inversion->frecuencia_pago }}</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Fecha de Inicio</x-td>
                                        <x-td>{{ $inversion->fecha_inicio }}</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Capital Final</x-td>
                                        <x-td>{{ $inversion->capital_final }}</x-td>
                                    </x-tr>
                                    <x-tr>
                                        <x-td>Fecha Vencimiento</x-td>
                                        <x-td>{{ $inversion->fecha_vencimiento }}</x-td>
                                    </x-tr>
                                </x-slot>



                            </x-table>
                        </div>
                    </div>
                </x-card>
            @endforeach
        @else
            <p>Aún no hay inversiones registradas.</p>
        @endif
    </div>

    <div x-show="activeTab === 'documentos'" class="mt-4" id="panel_documentos">
        <livewire:document-main :document_type="3" :document_group="$group->id" />
    </div>
    <div x-show="activeTab === 'socios'" class="mt-4" id="panel_socios">
        <livewire:group-members :group_id="$group->id" :project_id="$project_id" />
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
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name"
                        autocomplete="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-6">
                    <x-label for="description" value="Descripción" />
                    <x-textarea rows="4" id="description" type="text" class="mt-1 block w-full"
                        wire:model="description" autocomplete="description" />
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
