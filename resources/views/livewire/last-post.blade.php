<div>
    @if($post!=null)
    <div class="rounded-2xl shadow-lg overflow-hidden">
        @php
            $url_cover_image = asset('storage/' . $post->cover_image);
        @endphp
        <section class="bg-center bg-no-repeat bg-gray-400 bg-blend-multiply lg:min-h-[370px] h-full flex items-end"
            style="background-image: url('{{ $url_cover_image }}');">
            <div class="flex flex-col justify-end h-full p-2 md:p-4 lg:p-10">
                <div>
                    <div class="flex flex-wrap mb-4">
                        @foreach ($post->categories as $category)
                            <div
                                class="bg-blue-700 text-white text-xs font-semibold me-2 inline-block px-4 py-2 mb-4 rounded-2xl dark:bg-blue-900 dark:text-blue-300">
                                {{ $category->name }}
                            </div>
                        @endforeach
                    </div>

                    <a href="{{route("news.show",['slug'=>$post->slug])}}" class="mb-4 text-4xl text-white hover:text-yellow-500 transition md:text-3xl lg:text-4xl font-semibold">
                        {{ $post->title }}
                    </a>
                    
                    <div class="flex items-center text-white text-sm mt-1">
                        {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('F j, Y') }}
                        <i class="icon-comment-2 text-yellow-500"></i>
                        <span class="text-yellow-600">{{ $post->comments_count }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @else
    <x-card>
        Aún no tenemos noticias publicadas
    </x-card>
    @endif
</div>
