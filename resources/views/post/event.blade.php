<x-app-layout>
    <x-slot name="title">
        {{ $post->title }}
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <div class="p-2 lg:p-10">
        @php
            $date = \Illuminate\Support\Carbon::parse($post->starts_at);
            $endDate = $post->ends_at ? \Illuminate\Support\Carbon::parse($post->ends_at) : null;
        @endphp
        <x-card>
            <x-h3>{{ $post->title }}</x-h3>
            <div class="text-sm text-gray-500 my-2">
                <time datetime="{{ $date->format('Y-m-d') }}">
                    <i class="icon icon-calendar text-orange-600 font-bold"></i>
                    <span>{{ $date->translatedFormat('M j, g:i a') }}</span>
                    @if ($endDate)
                        - <span>{{ $endDate->translatedFormat('M j, g:i a') }}</span>
                    @endif
                </time>
            </div>

            <x-hr />
            @php
                $url_cover_image = asset('storage/' . $post->cover_image);
            @endphp
            <article>
                <p class="text-md text-gray-500">
                    <img src="{{ $url_cover_image }}" alt="Descripción de la imagen"
                        class="w-1/2 h-auto float-left rounded-lg shadow-md mr-8 mb-8">
                    {!! $post->content !!}
                </p>
            </article>
            <x-hr />
            <div class="grid grid-cols-4 gap-10">
                <div class="col-span-4 md:col-span-2 lg:col-span-1">

                    <x-dt>DETALLES</x-dt>
                    <x-dt>Comienza:</x-dt>
                    <x-label>{{ $date->translatedFormat('M j, g:i a') }}</x-label>

                    @if ($endDate)
                        <x-dt>Finaliza:</x-dt>
                        <x-label>{{ $endDate->translatedFormat('M j, g:i a') }}</x-label>
                    @endif
                </div>
                @if ($post->organizer || $post->phone || $post->email)
                    <div class="col-span-4 md:col-span-2 lg:col-span-1">

                        <x-dt>ORGANIZADOR</x-dt>
                        @if ($post->organizer)
                            <x-label>{{ $post->organizer }}</x-label>
                        @endif

                        @if ($post->phone)
                            <x-dt>Teléfono:</x-dt>
                            <x-label>{{ $post->phone }}</x-label>
                        @endif

                        @if ($post->email)
                            <x-dt>Correo electrónico:</x-dt>
                            <x-label>{{ $post->email }}</x-label>
                        @endif
                    </div>
                @endif
                @if ($post->location || $post->website)
                    <div class="col-span-4 md:col-span-2 lg:col-span-1">
                        @if ($post->location)
                            <x-dt>LOCAL</x-dt>
                            <x-label>{{ $post->location }}</x-label>
                        @endif

                        @if ($post->website)
                            <x-dt>Sitio Web:</x-dt>
                            <x-label>{{ $post->website }}</x-label>
                        @endif
                    </div>
                @endif
                <div class="col-span-4 md:col-span-2 lg:col-span-1">
                    @if ($post->map)
                    <iframe src="https://www.google.com/maps?q={{$post->map}}&output=embed" width="100%"></iframe>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
