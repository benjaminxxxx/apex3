<div>
    <!-- Componente del modal de confirmación -->
    <x-confirmation-modal id="confirmDeleteModal" wire:model="isDeleting">
        <x-slot name="title">
            Confirmar Eliminación
        </x-slot>
        <x-slot name="content">
            ¿Estás seguro que deseas eliminar este Post?
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="cancelDelete">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click="deletePost" class="ml-2">
                Eliminar
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
    <!-- Modal -->
    <x-dialog-modal wire:model="isFormOpen" maxWidth="full">
        <x-slot name="title">
            <div class="grid grid-cols-2 gap-2">

                <div class="col-span-2 lg:col-span-1">
                    @if ($postId == null)
                        Crear un nuevo Post({{ $type_post }})
                    @else
                        Editar un Post({{ $type_post }})
                    @endif
                </div>
                <div class="col-span-2 lg:col-span-1 text-right">
                    <x-outline-button wire:click="closeForm">
                        &times;
                    </x-outline-button>
                </div>
            </div>
        </x-slot>
        <x-slot name="content">
            <x-two-columns-8020>
                <x-slot name="content">

                    @if ($errors->has('error_message'))
                        <x-message-error>
                            {{ $errors->first('error_message') }}
                        </x-message-error>
                    @endif
                    <div class="mb-4">
                        <x-label for="title">Título del Post</x-label>
                        <x-input type="text" wire:model="title" wire:keyup="updateSlug" id="post-title" />
                        <x-input-error for="title"/>
                    </div>
                    <div class="mb-4">
                        <x-label for="slug">Slug</x-label>
                        <x-input type="text" wire:model="slug" id="slug" />
                        <x-input-error for="slug"/>
                    </div>
                
                    @if($type_post=='evento')
                    <div class="mb-4 grid grid-cols-3 gap-5">
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="starts_at">Fecha de inicio</x-label>
                            <x-datetime wire:model="starts_at" id="starts_at"/>
                            <x-input-error for="starts_at"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                         
                            <x-label for="ends_at">Fecha de cierre</x-label>
                            <x-datetime wire:model="ends_at" id="ends_at"/>>
                            <x-input-error for="ends_at"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="organizer">Organizador</x-label>
                            <x-input type="text" wire:model="organizer" id="organizer" />
                            <x-input-error for="organizer"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="phone">Telefono</x-label>
                            <x-input type="text" wire:model="phone" id="phone" />
                            <x-input-error for="phone"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="email">Email</x-label>
                            <x-input type="text" wire:model="email" id="email" />
                            <x-input-error for="email"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="location">Ubicación</x-label>
                            <x-input type="text" wire:model="location" id="location" />
                            <x-input-error for="location"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="website">Sitio Web</x-label>
                            <x-input type="text" wire:model="website" id="website" />
                            <x-input-error for="wbsite"/>
                        </div>
                        <div class="col-span-3 md:col-span-2 lg:col-span-1">
                            <x-label for="map">Mapa</x-label>
                            <x-input type="text" wire:model="map" id="map" />
                            <x-input-error for="map"/>
                        </div>
                    </div>
                    @endif
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
                                    height: '800px'
                                });
                                window.addEventListener('tinymce-update', event => {
                                    if (tinymce.get('content').getContent() !== event.detail[0]) {
                                        tinymce.get('content').setContent(event.detail[0]);
                                    }
                                });">
                        </textarea>
                        </div>
                        <x-input-error for="content"/>
                    </div>
                </x-slot>
                <x-slot name="aside">
                    <x-header>Publicación</x-header>
                    <x-button type="button" class="w-full" wire:click.prevent="store">Actualizar publicación</x-button>

                    <x-header>Tipo de publicación</x-header>
                    <x-select class="w-full" wire:model="type_post" wire:change="settype">
                        <option value="noticia">Noticia</option>
                        <option value="evento">Evento</option>
                        <option value="publicacion">Publicación</option>
                        <option value="foro">Foro</option>
                    </x-select>

                    <x-header>Categorías</x-header>
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
                                                wire:model="selected_categories" value="{{ $child->id }}" />
                                            <x-label for="subcategory_{{ $child->id }}" class="ml-2">
                                                {{ $child->name }}</x-label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <x-input-error for="selected_categories"/>
                    <x-header>Imagen destacada</x-header>
                    <div>
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
                            <div wire:loading wire:target="cover_image">Subiendo...</div>
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
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">PNG, JPG or GIF
                                            (MAX.
                                            800x400px)</p>
                                    </div>
                                    <input id="dropzone-file" type="file" wire:model="cover_image" class="hidden" />
                                </label>
                            </div>
                        @endif
                        <x-input-error for="cover_image"/>
                    </div>

                    <x-header>Extracto</x-header>
                    <div>
                        <x-label for="excerpt" class="ml-2"> ESCRIBE UN EXTRACTO (OPCIONAL)</x-label>
                        <x-textarea rows="3" id="excerpt" wire:model="excerpt"></x-textarea>
                    </div>

                    <x-header>Comentarios</x-header>
                    <div class="flex items-center mb-4">
                        <x-checkbox name="allow_comments" id="allow_comments" wire:model="allow_comments"
                            type="checkbox" />
                        <x-label for="allow_comments" class="ml-2"> Permitir comentarios</x-label>
                    </div>
                </x-slot>
            </x-two-columns-8020>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>


    <x-card class="shadow-lg mt-2 lg:mt-5">
        <div class="mb-2 md:mb-4">
            <x-button wire:click="openForm()">Agregar Nuevo Post</x-button>
        </div>
        <x-table>
            <x-slot name="thead">
                <tr>
                    <x-th value="Título" />
                    <x-th value="Categorías" />
                    <x-th value="Comentarios permitidos" />
                    <x-th value="Imagen principal" />
                    <x-th value="Tipo" />
                    <x-th value="Editar" />
                </tr>
            </x-slot>
            <x-slot name="tbody">
                @foreach ($posts as $post)
                    <x-tr>
                        <x-th>
                            <x-link href="{{route($post->type,['slug'=>$post->slug])}}" target="_blank" class="">{{ $post->title }}</x-link>
                        </x-th>
                        <x-th>
                            @if ($post->categories->isNotEmpty())
                                {{ $post->categories->pluck('name')->join(', ') }}
                            @endif
                        </x-th>
                        <x-th value="{{ $post->allow_comments == 1 ? 'Sí' : 'No' }}" />
                        <x-th>
                            @if (Storage::exists('public/' . $post->cover_image))
                                <img src="{{ asset('storage/' . $post->cover_image) }}"
                                    style="max-height: 60px; width: auto; object-fit: cover;">
                            @else
                                -
                            @endif
                        </x-th>
                        <x-th value="{{ $post->type }}" />
                        <td>
                            <x-secondary-button wire:click="edit({{ $post->id }})">
                                <i class="icon-pencil"></i>
                            </x-secondary-button>
                            @if ($post->status == '0')
                                <x-warning-button wire:click="enable({{ $post->id }})" class="ml-1">
                                    <i class="icon-block"></i>
                                </x-warning-button>
                            @else
                                <x-success-button wire:click="disable({{ $post->id }})" class="ml-1">
                                    <i class="icon-check"></i>
                                </x-success-button>
                            @endif
                            <x-danger-button wire:click="confirmDelete({{ $post->id }})" class="ml-1">
                                <i class="icon-trash"></i>
                            </x-danger-button>
                        </td>
                    </x-tr>
                @endforeach
            </x-slot>
        </x-table>
    </x-card>
    @if (session()->has('message'))
        <div id="toast-top-right"
            class="fixed flex mt-24 z-50 items-center w-full max-w-xs p-4 space-x-4 text-white bg-green-600 divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
            role="alert">
            <div class="text-sm font-normal flex w-full">

                {{ session('message') }}

                <button type="button" onclick="this.parentElement.parentElement.style.display='none';"
                    class="ms-auto -mx-1.5 -my-1.5 text-white hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-undo" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
