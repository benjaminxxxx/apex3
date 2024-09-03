<div>
    @if ($charts)
        @forelse ($charts as $chart)
            <x-card class="max-w-3xl mx-auto mt-2 relative">
                <div class="mb-4">
                    <div class="flex items-start">
                        <!-- User Avatar -->
                        <img class="w-12 h-12 rounded-full mr-4" src="{{ $chart->user->profile_photo_url }}"
                            alt="{{ $chart->user->fullName }}">
                        <div>
                            <!-- User Name and Time -->
                            <div class="text-lg font-semibold text-gray-800">{{ $chart->user->fullName }}</div>
                            <div class="text-sm text-gray-500">{{ $chart->created_at_human }}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <!-- chart Description -->
                    <div class="text-gray-600 mt-2">{{ $chart->description }}</div>
                    @php
                        $data = json_decode($chart->data, true); // Decodifica el JSON en un array
                    @endphp
                    @if ($chart->chart_type == 1)
                        <!-- General charts are viewed by all roles -->
                        @php
                            $encodeData = json_encode($data);
                        @endphp
                        <x-chart id="{{ $chart->code }}" :title="$chart->title" :charttype="$chart->chart_type" :type="$chart->type"
                            :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                    @else
                        @if (Auth::user()->role_id == 4)
                            <!-- Partner -->
                            @php
                                $data = json_decode($chart->data, true); // Decodifica el JSON en un array
                                if (isset($data[Auth::id()])) {
                                    $encodeData = json_encode($data[Auth::id()]);
                                }
                            @endphp
                            @if (isset($data[Auth::id()]))
                                <x-chart id="{{ $chart->code . '-' . Auth::id() }}" :title="$chart->title" :charttype="$chart->chart_type"
                                    :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                            @else
                                <x-label>No hay data para este usuario</x-label>
                            @endif
                        @elseif (Auth::user()->role_id == 3)
                            <!-- Manager -->
                            @php
                                $data = json_decode($chart->data, true);
                            @endphp
                            @foreach (Auth::user()->partners as $partner)
                                @if (isset($data[$partner->id]))
                                    @php
                                        $encodeData = json_encode($data[$partner->id]);
                                    @endphp
                                    <x-chart id="{{ $chart->code . '-' . $partner->id }}" :title="$chart->title"
                                        :charttype="$chart->chart_type" :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels"
                                        :showlegend="$chart->showlegend" />
                                @endif
                            @endforeach
                        @elseif (in_array(Auth::user()->role_id, [1, 2]))
                            <!-- Super Admin or Admin -->
                            <div x-data="{ open: false }" class="absolute right-0 top-0 text-right">
                                <!-- Dropdown Button -->
                                <button @click="open = !open"
                                    class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 focus:ring-0 focus:outline-none ext-sm p-4"
                                    type="button">
                                    <span class="sr-only">Open dropdown</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 16 3">
                                        <path
                                            d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                    </svg>
                                </button>
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.outside="open = false"
                                    class="z-10 text-base mr-5 list-none border-1 border-gray-500 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
                                    <ul class="py-2">
                                        <li>
                                            <a href="#" wire:click.prevent="edit('{{ $chart->code }}')"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Editar</a>
                                        </li>
                                        <li>
                                            <a href="#" wire:confirm="¿Está seguro que desea eliminar esta publicación?" wire:click="delete('{{ $chart->code }}')"
                                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Eliminar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $data = json_decode($chart->data, true);
                            @endphp
                            @foreach ($data as $userId => $chartData)
                                @php
                                    $encodeData = json_encode($chartData);
                                @endphp
                                <div  wire:ignore>
                                    <x-chart id="{{ $chart->code . '-' . $userId }}" :title="$chart->title" :charttype="$chart->chart_type"
                                        :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                                </div>
                                
                            @endforeach
                        @endif
                    @endif

                </div>

            </x-card>
        @empty
            <x-card>
                <x-label class="mt-10 text-center">Aún no se han publicado gráficos</x-label>
            </x-card>
        @endforelse
    @else
        <x-card>
            <x-label class="mt-10 text-center">Aún no se han publicado gráficos</x-label>
        </x-card>
    @endif
    <x-dialog-modal wire:model.live="isFormOpen" maxWidth="lg">
        <x-slot name="title">
            Editar publicación
            <button wire:click="$set('isFormOpen', false)"
                class="focus:border-0 focus:outline-none border-0 border-none shadow-none rounded-lg text-gray-600 w-10 h-10 absolute right-0 top-0 bg-white hover:bg-gray-200 !font-2xl font-bold !p-0 flex items-center justify-center"
                wire:loading.attr="disabled">
                x
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="flex sm:items-start">
                <div class="flex-shrink-0">
                    <!-- User Avatar -->
                    <img class="w-14 h-14 rounded-full mr-2" src="{{ Auth::user()->profile_photo_url }}"
                        alt="User Avatar">
                </div>
                <div class="mt-3 mt-0 ml-4 text-left w-full">
                    <!-- User Input -->
                    <x-label class="font-semibold">{{ Auth::user()->fullName }}</x-label>
                   
                </div>
            </div>
            <div class="grid grid-cols-7 gap-5">
                <div class="col-span-7 lg:col-span-5">
                    <div class="my-4">
                        <x-label for="title">Título del Gráfico</x-label>
                        <x-input type="text" wire:model="title" id="post-title" />
                        <x-input-error for="title" />
                    </div>
                    <div class="my-4">
                        <x-label for="description">Descripcón del Gráfico</x-label>
                        <x-input type="text" wire:model="description" id="post-description" />
                        <x-input-error for="description" />
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-secondary-button class="" wire:click="cancel">
                Cancelar
            </x-secondary-button>
            <x-button class="ml-3" wire:click="store" wire:loading.attr="disabled">
                Editar publicación
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
