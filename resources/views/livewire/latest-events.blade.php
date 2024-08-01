<div>
    <x-card>
        <x-h2>Últimos Eventos</x-h2>
        <x-hr />
        @if ($events->isNotEmpty())
            @foreach ($events as $event)
                <div class="mb-4">
                    @if ($widthImage)
                        <img src="{{ $event->cover_image_url }}"
                            class="w-full h-32 object-cover rounded-lg overflow-hidden">
                    @endif
                    <div class="text-gray-500 text-xs my-2">
                        @if ($event->categories->isNotEmpty())
                            <span
                                class="font-semibold uppercase text-gray-900">{{ $event->categories->first()->name }}</span>
                        @endif
                        <span>{{ $event->created_at->format('d M, Y') }}</span>
                    </div>
                    <a href="{{ route('event', ['slug' => $event->slug]) }}"
                        class="text-steal-800 font-semibold text-lg">{{ $event->title }}</a>

                </div>
            @endforeach
        @else
            <p>No hay eventos disponibles.</p>
        @endif
        @if ($withShowMore)
            <a href="{{ route('events') }}" class="mt-2 text-steal-600 p-2 inline-block">Ver todo <i
                    class="icon-arrow-right"></i></a>
        @endif
    </x-card>
</div>
