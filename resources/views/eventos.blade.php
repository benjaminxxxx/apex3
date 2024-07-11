<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <livewire:chat :popup="true"/>
    <div class="p-2 md:p-10">
        <x-card>

            <div>
                <div class="flex items-center">
                    <x-h2>Próximos Eventos</x-h2>
                    <x-a class="ml-2" href="{{ route('post.new', ['type' => 'evento']) }}">Publicar un nuevo
                        Evento</x-a>
                </div>
                <x-hr />
                @if ($posts->count() > 0)
                    @foreach ($posts as $post)
                        <!-- Mes y año -->
                        @php
                            $date = \Illuminate\Support\Carbon::parse($post->starts_at);
                            $endDate = $post->ends_at ? \Illuminate\Support\Carbon::parse($post->ends_at) : null;
                        @endphp
                        <h2 class="text-xs w-full mb-4">
                            <time datetime="{{ $date->format('Y-m') }}">
                                {{ $date->translatedFormat('F Y') }}
                            </time>
                        </h2>

                        <!-- Evento -->
                        <div class="flex flex-col lg:flex-row">
                            <!-- Fecha -->
                            <div class="py-4 w-full lg:w-16 flex-none">
                                @php
                                    $dayOfWeek = $date->translatedFormat('D'); // Día de la semana en español, abreviado
                                    $dayOfMonth = $date->day; // Número del día del mes
                                @endphp
                                <time datetime="{{ $date->format('Y-m-d') }}" aria-hidden="true">
                                    <span
                                        class="block text-center text-gray-500 uppercase text-xs">{{ $dayOfWeek }}</span>
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
                                                <a href="{{ route($post->type,['slug'=>$post->slug]) }}"
                                                    class="text-gray-900 hover:underline">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-700 my-3">
                                                {{ $post->address }}
                                            </p>
                                        </header>
                                        <div class="hidden lg:block text-gray-500 text-sm">
                                            @php
                                                $excerpt =
                                                    $post->excerpt ?? strip_tags(Str::words($post->content, 30, '...'));
                                            @endphp

                                            <p>{{ $excerpt }}</p>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <!-- Precio -->
                            <div class="flex items-start w-full lg:w-[300px] flex-none">
                                <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}"
                                    class="w-full h-auto rounded-lg">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>



        </x-card>
    </div>
</x-app-layout>
