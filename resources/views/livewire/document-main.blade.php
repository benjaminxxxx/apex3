<div>
    @if (Auth::user()->role_id!=4)
        <x-card>
            <div class="flex items-center">
                <!-- User Avatar -->
                <img class="w-12 h-12 rounded-full mr-4" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}">
                <!-- Input for Document Title -->
                <x-input type="text" wire:click="$set('openCreateNewDocument', true)"
                    placeholder="Escribe el título de tu documento..." class="w-full" />
            </div>
        </x-card>
    @endif


    @if ($documents)
        @foreach ($documents as $document)
            <x-card class="max-w-3xl mx-auto mt-2 relative">
                @if ($document->creator->id == Auth::id())
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
                                <!--<li>
                        <a href="#" wire:click="edit('{{ $document->code }}')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                    </li>-->
                                <li>
                                    <a href="#" wire:click="delete('{{ $document->code }}')"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Eliminar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
                <!-- Document Post -->
                <div class="flex items-start">
                    <!-- User Avatar -->
                    <img class="w-12 h-12 rounded-full mr-4" src="{{ $document->creator->profile_photo_url }}"
                        alt="User Avatar">
                    <div>
                        <!-- User Name and Time -->
                        <div class="text-lg font-semibold text-gray-800">{{ $document->creator->fullName }}</div>
                        <div class="text-sm text-gray-500">{{ $document->created_at_human }}</div>
                    </div>
                    <!-- Document Type Icon -->
                    <div class="ml-auto">
                        <a href="{{ $document->file_url }}" class="mr-16 mt-10" target="_blank">
                            <img class="w-8 h-8" src="{{ $document->file_photo_url }}">
                        </a>
                    </div>
                </div>
                <!-- Document Title -->
                <div class="mt-4">
                    <!-- Document Description -->
                    <div class="text-gray-600 mt-2">{{ $document->description }}</div>
                </div>
                <!-- Additional Document Posts -->
                <!-- Repeat the above block for each document post -->
            </x-card>
        @endforeach
    @endif

    <x-dialog-modal wire:model.live="openCreateNewDocument">
        <x-slot name="title">
            Cargar nuevo Documento
            <button wire:click="$set('openCreateNewDocument', false)"
                class="focus:border-0 focus:outline-none border-0 border-none shadow-none rounded-lg text-gray-600 w-10 h-10 absolute right-0 top-0 bg-white hover:bg-gray-200 !font-2xl font-bold !p-0 flex items-center justify-center"
                wire:loading.attr="disabled">
                x
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="sm:flex sm:items-start">
                <div class="sm:flex-shrink-0">
                    <!-- User Avatar -->
                    <img class="w-14 h-14 rounded-full mr-2" src="{{ Auth::user()->profile_photo_url }}"
                        alt="User Avatar">
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <!-- User Input -->
                    <x-label class="font-semibold">{{ Auth::user()->fullName }}</x-label>
                    <!-- Combo Box -->
                    <select class="w-auto mt-1 rounded-2xl bg-gray-200 border-0 text-xs px-5 py-2"
                        wire:model="visibility">
                        @if ($document_type == '1')
                            <option value="">Público</option>
                            <option value="2">Administradores</option>
                            <option value="3">Gestores</option>
                            <option value="4">Socios</option>
                        @endif
                        @if ($document_type == '2')
                            <option value="">Todos los miembros</option>
                            <option value="2">Administradores</option>
                            <option value="3">Gestores del proyecto</option>
                            <option value="4">Socios del proyecto</option>
                        @endif
                        @if ($document_type == '3')
                            <option value="">Todos los socios</option>
                        @endif
                    </select>
                </div>
            </div>
            <textarea class="w-full mt-2 border-0 p-0 focus:border-none focus:outline-0 focus:ring-0" rows="3"
                placeholder="Descripción del documento" wire:model="description"></textarea>
            <div class="relative flex justify-end">
                @if ($document_upload)
                    @php
                        $documentType = null; // Lógica para determinar el tipo de documento a partir del archivo subido
                        $extension = $document_upload ? $document_upload->getClientOriginalExtension() : '';
                        switch ($extension) {
                            case 'pdf':
                                $documentType = 'icon-pdf';
                                break;
                            case 'doc':
                            case 'docx':
                                $documentType = 'icon-word';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $documentType = 'icon-excel';
                                break;
                            default:
                                $documentType = 'default-icon'; // Icono por defecto
                                break;
                        }
                    @endphp
                    <img src="{{ asset('images/' . $documentType . '.svg') }}"
                        class="max-w-20 h-auto object-cover mr-10">

                    <button type="button" class="absolute focus:outline-0 right-0 top-0 " wire:click="deleteFile">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </button>
                @else
                    <div wire:loading wire:target="document_upload" class="absolute text-xs">Subiendo...</div>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                        class="font-semibold">Clic para cargar</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Docx, Pdf, Xls</p>
                            </div>
                            <input id="dropzone-file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                                wire:model="document_upload" class="hidden" />
                        </label>
                    </div>
                @endif
                <x-input-error for="document_upload" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button class="w-full" wire:click="store" wire:loading.attr="disabled" :disabled="$document_upload === null">
                Publicar documento
            </x-button>
        </x-slot>
    </x-dialog-modal>
    @if (session()->has('message'))
        <x-toast class="bg-green-600">
            {{ session('message') }}
        </x-toast>
    @endif
    @if (session()->has('error'))
        <x-toast class="bg-red-600">
            {{ session('error') }}
        </x-toast>
    @endif
</div>
