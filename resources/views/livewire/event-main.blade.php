<div>
    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
        <x-card>
            <div class="flex items-center">
                <!-- User Avatar -->
                <img class="w-12 h-12 rounded-full mr-4" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}">
                <!-- Input for Document Title -->
                <x-input type="text" wire:click="$set('openCreateNewEvent', true)"
                    placeholder="Escribe el título de tu evento..." class="w-full" />
            </div>
        </x-card>
    @endif

    @if ($events)
        @foreach ($events as $event)
            <x-card class="max-w-3xl mx-auto mt-2 relative">
                @if ($event->creator->id == Auth::id())
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
                                    <a href="#" wire:click.prevent="edit('{{ $event->code }}')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                </li>
                                <li>
                                    <a href="#" wire:click.prevent="delete('{{ $event->code }}')"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Eliminar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="flex items-start">
                    <!-- User Avatar -->
                    <img class="w-12 h-12 rounded-full mr-4" src="{{ $event->creator->profile_photo_url }}"
                        alt="{{ $event->creator->fullName }}">
                    <div>
                        <!-- User Name and Time -->
                        <div class="text-lg font-semibold text-gray-800">{{ $event->creator->fullName }}</div>
                        <div class="text-sm text-gray-500">{{ $event->created_at_human }}</div>
                    </div>
                </div>
                @php
                    $date = \Illuminate\Support\Carbon::parse($event->start_date);
                    $endDate = $event->end_date ? \Illuminate\Support\Carbon::parse($event->end_date) : null;
                @endphp

                <!-- Evento -->
                <div class="flex flex-col lg:flex-row">
                    <!-- Fecha -->
                    <div class="py-4 w-full lg:w-16 flex-none">
                        @php
                            $dayOfWeek = $date->translatedFormat('D'); // Día de la semana en español, abreviado
                            $dayOfMonth = $date->day; // Número del día del mes
                        @endphp

                        <time datetime="{{ $date->format('Y-m-d') }}" aria-hidden="true">
                            <span class="block text-center text-gray-500 uppercase text-xs">{{ $dayOfWeek }}</span>
                            <span class="block text-center text-md font-400 mt-2">{{ $dayOfMonth }}</span>
                        </time>
                    </div>

                    <!-- Detalles del evento -->
                    <div class="flex-1 p-4">
                        <article class="flex flex-col lg:flex-row">


                            <!-- Información del evento -->
                            <div class="flex-1">
                                <header class="mb-2">
                                    <div class="text-sm text-gray-500 mb-1">
                                        <time datetime="{{ $date->format('Y-m-d') }}">
                                            <i class="icon icon-calendar text-orange-600 font-bold"></i>
                                            <span>{{ $date->translatedFormat('M j, g:i a') }}</span>
                                            @if ($endDate)
                                                - <span>{{ $endDate->translatedFormat('M j, g:i a') }}</span>
                                            @endif
                                        </time>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-1">
                                        <a href="{{ route('event', ['slug' => $event->slug]) }}"
                                            class="text-gray-900 hover:underline">
                                            {{ $event->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-700 my-3">
                                        {{ $event->address }}
                                    </p>
                                </header>
                                <div class="hidden lg:block text-gray-500 text-sm">

                                    <p>{{ strip_tags(Str::words($event->content, 30, '...')) }}</p>
                                </div>
                            </div>
                        </article>
                    </div>
                    @if ($event->cover_image)
                        <div class="flex items-start w-full lg:w-[300px] flex-none">
                            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}"
                                class="w-full h-auto rounded-lg">
                        </div>
                    @endif
                </div>
                <!-- Badges for User Roles -->
                <div class="flex mt-4 justify-end">
                    @foreach ($event->categories as $category)
                        @if ($category->id != 1)
                            <span class="bg-amber-400 text-gray-700 text-xs mr-2 px-3 py-1 rounded">
                                {{ $category->name }}
                            </span>
                        @endif
                    @endforeach
                </div>
                <div class="flex mt-2 justify-end">
                    @foreach ($event->roles as $role)
                        @if ($role->id != 1)
                            <span class="bg-cyan-600 text-white text-xs mr-2 px-3 py-1 rounded">
                                {{ $role->name }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </x-card>
        @endforeach
        @if ($events->count() == 0)
            <x-label class="mt-10 text-center">Aún no hay eventos publicados.</x-label>
        @endif
    @endif

    <x-dialog-modal wire:model.live="openCreateNewEvent" maxWidth="full">
        <x-slot name="title">
            Crear nuevo Evento
            <button wire:click="$set('openCreateNewEvent', false)"
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
                        @if ($event_type == '1')
                            <option value="">Público</option>
                            <option value="3">Gestores</option>
                            <option value="4">Socios</option>
                        @endif
                        @if ($event_type == '2')
                            <option value="">Todos los miembros</option>
                            <option value="3">Gestores del proyecto</option>
                            <option value="4">Socios del proyecto</option>
                        @endif
                    </select>

                </div>
            </div>
            <div class="grid grid-cols-7 gap-5">
                <div class="col-span-7 lg:col-span-5">
                    <div class="my-4">
                        <x-label for="title">Título del Evento</x-label>
                        <x-input type="text" wire:model="title" wire:keyup="updateSlug" id="post-title" />
                        <x-input-error for="title" />
                    </div>
                    <div class="mb-4">
                        <x-label for="slug">Slug</x-label>
                        <x-input type="text" wire:model="slug" id="slug" />
                        <x-input-error for="slug" />
                    </div>
                    <div class="mb-4">
                        <x-label for="content">Contenido</x-label>

                        <div wire:ignore>
                            <textarea class="content form-input rounded-md shadow-sm mt-1 block w-full " id="content" name="content"
                                rows="20" wire:model.debounce.9999999ms="content" wire:key="content" x-data x-ref="content"
                                x-init="tinymce.init({
                                    path_absolute: '/',
                                    selector: 'textarea.content',
                                    plugins: [
                                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                        'searchreplace wordcount visualblocks visualchars code fullscreen ',
                                        'insertdatetime media nonbreaking save table directionality',
                                        'emoticons template paste textpattern  imagetools help  '
                                    ],
                                    toolbar: 'insertfile undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | help ',
                                    relative_urls: false,
                                    remove_script_host: false,
                                    document_base_url: '{{ config('app.url') }}/',
                                    language: 'es',
                                    setup: function(editor) {
                                        editor.on('init change', function() {
                                            editor.save();
                                        });
                                        editor.on('change', function(e) {
                                            @this.set('content', editor.getContent());
                                        });
                                    },
                                    height: '300px'
                                });
                                window.addEventListener('tinymce-update', event => {
                                    if (tinymce.get('content').getContent() !== event.detail[0]) {
                                        tinymce.get('content').setContent(event.detail[0]);
                                    }
                                });">
                        </textarea>
                        </div>
                        <x-input-error for="content" />
                    </div>
                    <div class="mb-4 grid grid-cols-3 gap-5">

                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="organizer">Organizador</x-label>
                            <x-input type="text" wire:model="organizer" id="organizer" />
                            <x-input-error for="organizer" />
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="phone">Telefono</x-label>
                            <x-input type="text" wire:model="phone" id="phone" />
                            <x-input-error for="phone" />
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="email">Email</x-label>
                            <x-input type="text" wire:model="email" id="email" />
                            <x-input-error for="email" />
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="location">Ubicación</x-label>
                            <x-input type="text" wire:model="location" id="location" />
                            <x-input-error for="location" />
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="website">Sitio Web</x-label>
                            <x-input type="text" wire:model="website" id="website" />
                            <x-input-error for="wbsite" />
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="map">Mapa</x-label>
                            <x-input type="text" wire:model="map" id="map" />
                            <x-input-error for="map" />
                        </div>
                    </div>
                </div>

                <div class="col-span-7 lg:col-span-2">
                    <div class="relative">
                        @if ($image_path || $cover_image)
                            @if ($image_path != null)
                                <!-- Cuando la imagen ya está guardada -->
                                <img src="{{ asset('storage/' . $image_path) }}"
                                    style="max-height: 160px; width: 100%; object-fit: cover;">
                            @endif
                            @if ($cover_image != null)
                                <!-- Cuando se está previsualizando una imagen temporal -->
                                <img src="{{ $cover_image->temporaryUrl() }}"
                                    style="max-height: 160px; width: 100%; object-fit: cover;">
                            @endif
                            <x-outline-button type="button" class="w-full mt-2" wire:click="deleteImage">Eliminar
                                imagen</x-outline-button>
                        @else
                            <x-dropzone text="Clic para cargar una portada" formats="Jpg, Png, Svg"
                                accept=".jpg,.png,.svg" wire:model="cover_image" />
                        @endif
                        <x-input-error for="cover_image" />
                        <div class="mt-2">
                            <x-label for="start_date">Fecha de inicio</x-label>
                            <x-datetime wire:model="start_date" id="start_date" />
                            <x-input-error for="start_date" />
                        </div>
                        <div class="mt-2">

                            <x-label for="end_date">Fecha de cierre</x-label>
                            <x-datetime wire:model="end_date" id="end_date" />
                            <x-input-error for="end_date" />
                        </div>
                        <div class="mt-2">
                            <x-label>Categorías</x-label>
                            @foreach ($categories as $category)
                                <div class="category">
                                    <div class="flex items-center mt-4 mb-2">
                                        <x-checkbox id="category_{{ $category->id }}" type="checkbox"
                                            wire:model="selected_categories" value="{{ $category->id }}" />
                                        <x-label for="category_{{ $category->id }}" class="ml-2">
                                            {{ $category->name }}</x-label>
                                    </div>
                                    @if ($category->children->isNotEmpty())
                                        <div class="ml-4">
                                            @foreach ($category->children as $child)
                                                <div class="flex items-center mb-2 subcategory">
                                                    <x-checkbox id="subcategory_{{ $child->id }}" type="checkbox"
                                                        wire:model="selected_categories"
                                                        value="{{ $child->id }}" />
                                                    <x-label for="subcategory_{{ $child->id }}" class="ml-2">
                                                        {{ $child->name }}</x-label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            <x-input-error for="selected_categories" />
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-button class="w-full" wire:click="store" wire:loading.attr="disabled">
                Publicar evento
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
