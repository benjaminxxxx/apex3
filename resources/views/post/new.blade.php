<x-app-layout>
    <x-slot name="title">
        {{ $post->title }}
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-two-columns-content-aside>
        <x-slot name="content">

            <div class="rounded-2xl bg-white shadow-2xl overflow-hidden">
                @php
                    $url_cover_image = asset('storage/' . $post->cover_image);
                @endphp
                <section
                    class="bg-center bg-no-repeat bg-gray-400 bg-blend-multiply lg:min-h-[370px] h-full flex items-end"
                    style="background-image: url('{{ $url_cover_image }}');">
                    <div class="flex flex-col justify-end h-full p-2 md:p-4 lg:p-10">
                        <div>
                            <div class="flex flex-wrap mb-4">
                                @foreach($post->categories as $category)
                                    <div class="bg-blue-700 text-white text-xs font-semibold me-2 inline-block px-4 py-2 mb-4 rounded-2xl dark:bg-blue-900 dark:text-blue-300">
                                        {{ $category->name }}
                                    </div>
                                @endforeach
                            </div>
                            
                            <h1 class="mb-4 text-4xl text-white md:text-3xl lg:text-4xl font-semibold">
                                {{$post->title}}
                            </h1>

                            <div class="text-white text-sm mb-4">
                                {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('F j, Y') }}
                            </div>
                        </div>
                    </div>
                </section>
                <div class="p-2 md:p-10 text-md text-gray-500">
                    {!! $post->content !!}
                </div>
            </div>



        </x-slot>
        <x-slot name="aside">
            @livewire("latest-news")

                @livewire("tags-news")
        </x-slot>
    </x-two-columns-content-aside>
</x-app-layout>
