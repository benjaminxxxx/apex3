<div>
    <x-card>
        <x-h2>Últimas noticias</x-h2>
        <x-hr />
        <div class="" id="latest-news">
            @if ($noticias->isNotEmpty())
                @foreach ($noticias as $noticia)
                    <div class="mb-4">
                        @if ($widthImage)
                            <img src="{{ $noticia->cover_image_url }}"
                                class="w-full h-32 object-cover rounded-lg overflow-hidden">
                        @endif
                        <div class="text-gray-500 text-xs my-2">
                            @if ($noticia->categories->isNotEmpty())
                                <span
                                    class="font-semibold uppercase text-gray-900">{{ $noticia->categories->first()->name }}</span>
                            @endif
                            <span>{{ $noticia->created_at->format('d M, Y') }}</span>
                        </div>
                        <a href="{{ route('news.show', ['slug' => $noticia->slug]) }}"
                            class="text-steal-800 font-semibold text-lg">{{ $noticia->title }}</a>
                       
                    </div>
                @endforeach
            @else
                <p>No hay noticias disponibles.</p>
            @endif
        </div>
        @if ($withShowMore)
            <a href="{{route("news")}}" class="mt-2 text-steal-600 p-2 inline-block">Ver todo <i class="icon-arrow-right"></i></a>
        @endif
    </x-card>
</div>
