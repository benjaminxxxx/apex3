<div>
    <x-card>
        <x-h2 value="Tags" />
        <x-hr />
        <div class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <a href="#"
                    class="bg-gray-100 text-gray-600 px-3 py-1 text-sm uppercase rounded-full">{{ $tag->name }}</a>
            @endforeach
        </div>
    </x-card>
</div>
