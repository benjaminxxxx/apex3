<div>
    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
        <x-card>
            <div class="flex items-center">

                <img class="w-12 h-12 rounded-full mr-4" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}">

                <x-input type="text" wire:click="setOpenNewArticle" placeholder="Escribe el título de tu noticia..."
                    class="w-full" />
            </div>
        </x-card>
    @endif
    <div id="posts-container" class="gap-5 grid grid-cols-2">
        @if ($news)
            @foreach ($news as $article)
                <div data-code="{{ $article->code }}"
                    class="mb-4 rounded-lg overflow-hidden shadow-lg bg-white  relative col-span-2 md:col-span-1">

                    @if ($article->cover_image)
                        <img src="{{ asset('uploads/' . $article->cover_image) }}" class="w-full max-h-32 object-cover" alt="">
                    @endif
                    <div class="p-2 md:p-10 relative">
                        @if ($article->creator->id == Auth::id())
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
                                            <a href="#" wire:click.prevent="edit('{{ $article->code }}')"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                        </li>
                                        <li>
                                            <a href="#" wire:click.prevent="delete('{{ $article->code }}')"
                                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Eliminar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="text-gray-500 text-sm">
                            @if ($article->categories->isNotEmpty())
                                {{ $article->categories->first()->name }}
                            @endif
                            <small>{{ $article->created_at->format('M d, Y') }}</small>
                        </div>
                        <a href="{{ route('news.show', ['slug' => $article->slug]) }}"
                            class="text-steal-800 font-semibold">{{ $article->title }}</a>

                        <div class="excerpt">
                            @php
                                $excerpt = $article->excerpt ?? strip_tags(Str::words($article->content_noticia, 30, '...'));
                            @endphp
                            {{ $excerpt }}
                        </div>
                    </div>


                </div>
            @endforeach
            @if ($news->count() == 0)
                <x-label class="mt-10 text-center">Aún no hay noticias publicados.</x-label>
            @endif
        @endif
    </div>
    <div id="load-more" wire:click="loadMore" class="flex justify-center mt-4">
        <x-button class="append-button">Más noticias</x-button>
    </div>
    <x-dialog-modal wire:model.live="openCreateNewNews" maxWidth="full">
        <x-slot name="title">
            Crear Nueva Noticia
            <button wire:click="$set('openCreateNewNews', false)"
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
                    <!-- Combo Box -->
                    <select class="w-auto mt-1 rounded-2xl bg-gray-200 border-0 text-xs px-5 py-2"
                        wire:model="visibility">
                        @if ($news_type == '1')
                            <option value="">Público</option>
                            <option value="3">Gestores</option>
                            <option value="4">Socios</option>
                        @endif
                        @if ($news_type == '2')
                            <option value="">Este proyecto</option>
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
                            <textarea class="content_noticia form-input rounded-md shadow-sm mt-1 block w-full " id="content_noticia" name="content_noticia"
                                rows="20" wire:model.debounce.9999999ms="content_noticia" wire:key="content_noticia" x-data x-ref="content_noticia"
                                x-init="tinymce.init({
                                    path_absolute: '/',
                                    selector: 'textarea.content_noticia',
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
                                            @this.set('content_noticia', editor.getContent());
                                        });
                                    },
                                    height: '300px'
                                });
                                window.addEventListener('tinymce-update', event => {
                                    if (tinymce.get('content_noticia').getContent() !== event.detail[0]) {
                                        tinymce.get('content_noticia').setContent(event.detail[0]);
                                    }
                                });">
                        </textarea>
                        </div>
                        <x-input-error for="content_noticia" />
                    </div>

                </div>

                <div class="col-span-7 lg:col-span-2">
                    <div class="relative">
                        @if ($image_path || $cover_image)
                            @if ($image_path != null)
                                <!-- Cuando la imagen ya está guardada -->
                                <img src="{{ asset('uploads/' . $image_path) }}"
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
                Publicar noticia
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
