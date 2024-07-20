<x-app-layout>
    <x-slot name="title">
        Noticias
    </x-slot>

    <x-slot name="header">

    </x-slot>
    <livewire:chat :popup="true"/>
    <div class="p-2 md:py-10 md:pr-10 md:pl-5 mt-2 md:mt-5">
        <div class="lg:flex">
            <div class="lg:flex-1 justify-center ">
                <div class="flex items-center mb-2 md:pl-4  md:mb-10 ">
                    <h2 class="font-bold text-3xl text-gray-800 leading-tight inline-block">
                        Noticias
                    </h2>
                    @if(Auth::user()->hasPermission('add_news'))
                    <x-a href="{{ route('post.new', ['type' => 'noticia']) }}" class="ml-2">Publicar noticia</x-a>
                    @endif
                </div>
                <div class="w-full">
                    <div id="posts-container" class="">
                        <div class='grid__col-sizer'></div>

                        @if ($posts->isNotEmpty())
                            @foreach ($posts as $post)
                                <div class="p-2 md:p-4 grid__item">
                                    <div class="mb-4 rounded-lg p-2 md:p-10 shadow-lg bg-white  relative">
                                        <img src="{{ asset('storage/' . $post->cover_image) }}" class="w-full"
                                            alt="">
                                        <div class="text-gray-500 text-sm">
                                            @if ($post->categories->isNotEmpty())
                                                {{ $post->categories->first()->name }}
                                            @endif
                                            <small>{{ $post->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <a href="{{route('noticia',['slug'=>$post->slug])}}" class="text-steal-800 font-semibold">{{ $post->title }}</a>
                                        <!--<div class="flex items-center text-gray-500 text-sm mt-1">
                                            <i class="icon-comment-light text-orange-600"></i>
                                            (0)
                                        </div>-->
                                        <div class="excerpt">
                                            @php
                                                $excerpt =
                                                    $post->excerpt ?? strip_tags(Str::words($post->content, 30, '...'));
                                            @endphp
                                            {{ $excerpt }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No hay noticias disponibles.</p>
                        @endif
                    </div>
                    <div id="load-more" class="flex justify-center mt-4">
                        <x-button class="append-button">Más noticias</x-button>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-[400px] lg:pl-3">
                <x-card>
                    <x-h2 value="Buscar" />
                    <div class="border my-2 md:my-5"></div>
                    <div class="">

                        <form class="max-w-md mx-auto" method="get">
                            <label for="default-search"
                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="hidden" name="category_name" value="noticias">
                                <input type="search" id="default-search"
                                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:border-slate-500"
                                    placeholder="Buscar..." name="s" required />
                                <button type="submit"
                                    class="text-white absolute end-2.5 bottom-2.5 bg-slate-800 hover:bg-slate-900 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-800">Buscar</button>
                            </div>
                        </form>
                    </div>
                </x-card>

                <x-card>
                    <x-h2 value="Categorías" />
                    <x-hr />
                    <div class="">
                        <ul id="categories-list" class="list-general">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="#" class="text-blue-500">
                                        {{ $category->name }}
                                    </a>
                                    ({{ $category->posts_count }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-card>

                @livewire("latest-news")

                @livewire("tags-news")
                
                
            </div>
        </div>
    </div>
    <style>
        .grid__item,
        .grid__col-sizer {
            width: 50%;
        }

        .grid__item {
            float: left;
        }
    </style>
    @push('scripts')
        <script>
            const grid = document.querySelector('#posts-container');

            const msnry = new Masonry(grid, {
                columnWidth: '.grid__col-sizer',
                itemSelector: 'none',
                percentPosition: true,
                stagger: 30,
                visibleStyle: {
                    transform: 'translateY(0)',
                    opacity: 1
                },
                hiddenStyle: {
                    transform: 'translateY(100px)',
                    opacity: 0
                },
            });

            imagesLoaded(grid, function() {
                msnry.options.itemSelector = '.grid__item';
                let items = grid.querySelectorAll('.grid__item');
                msnry.appended(items);
            });

            const appendButton = document.querySelector('.append-button');
            var offset = 5;

            function stripHtml(html) {
                let temporalDivElement = document.createElement("div");
                temporalDivElement.innerHTML = html;
                return temporalDivElement.textContent || temporalDivElement.innerText || "";
            }

            function loadNotices() {
                const url = "{{ route('news-load-more') }}";
                fetch(url + '?offset=' + offset)
                    .then(response => response.json())
                    .then(data => {
                        var elems = [];
                        var fragment = document.createDocumentFragment();
                        data.forEach(post => {
                            var postElement = document.createElement('div');
                            postElement.classList.add('md:p-4', 'p-2', 'grid__item');

                            postElement.innerHTML = `
                        <div class="mb-4 rounded-lg p-2 md:p-10 shadow-lg bg-white relative">
                             <img src="${post.cover_image ? post.cover_image : 'https://picsum.photos/600/400'}" class="w-full" alt="">
                            <div class="text-gray-500 text-sm">
                                ${post.categories.length ? post.categories[0].name : ''}
                                <small>${new Date(post.created_at).toLocaleDateString()}</small>
                            </div>
                            <a href="#" class="text-steal-800 font-semibold">${post.title}</a>
                            <div class="flex items-center text-gray-500 text-sm mt-1">
                                <i class="icon-comment-light text-orange-600"></i>
                                (${post.comments_count || 0})
                            </div>
                            <div class="excerpt">
            ${post.excerpt || (post.content ? stripHtml(post.content).substring(0, 100) + '...' : '')}
        </div>
                            </div>
                        `;
                            fragment.appendChild(postElement);
                            elems.push(postElement);
                        });
                        grid.appendChild(fragment);
                        msnry.appended(elems);
                        msnry.layout();
                        offset += 5;
                    });
            }
            appendButton.addEventListener('click', function() {
                loadNotices();
                /*var elems = [];
                    var fragment = document.createDocumentFragment();
                
                    for ( var i = 0; i < 3; i++ ) {
                        var elem = getItemElement();
                        fragment.appendChild(elem);
                        elems.push(elem);
                    }

                    grid.appendChild(fragment);
                    msnry.appended(elems);*/
            });

            function getItemElement() {
                var elem = document.createElement('div');
                elem.className = 'grid__item';
                elem.innerHTML = '<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/82/flight-formation.jpg">';
                return elem;
            }
        </script>
    @endpush
</x-app-layout>
