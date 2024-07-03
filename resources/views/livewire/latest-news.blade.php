<div>
    <x-card>
        <x-h2>Últimas noticias</x-h2>
        <x-hr />
        <div class="" id="latest-news">
            @if ($noticias->isNotEmpty())
                @foreach ($noticias as $noticia)
                    <div class="mb-4">
                        <div class="text-gray-500 text-sm">
                            @if ($noticia->categories->isNotEmpty())
                                {{ $noticia->categories->first()->name }}
                            @endif
                            <small>{{ $noticia->created_at->format('d M, Y') }}</small>
                        </div>
                        <a href="{{ route("noticia",['slug'=> $noticia->slug]) }}" class="text-steal-800 font-semibold">{{ $noticia->title }}</a>
                        <div class="flex items-center text-gray-500 text-sm mt-1">
                            <i class="icon-comment-light text-orange-600"></i>
                            ({{ $noticia->comments_count }})
                        </div>
                    </div>
                @endforeach
            @else
                <p>No hay noticias disponibles.</p>
            @endif
        </div>
        <a href="#" class="mt-2 text-steal-600 p-2 inline-block">Ver todo <i
                class="icon-arrow-right"></i></a>

    </x-card>
</div>
