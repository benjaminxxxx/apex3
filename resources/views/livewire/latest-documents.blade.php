<div>
    <x-card>
        <x-h2>Últimos Documentos</x-h2>
        <x-hr />
        @if ($documents->isNotEmpty())
            @foreach ($documents as $document)
                <div class="mb-4">
                    <div class="flex items-start">
                        <!-- User Avatar -->
                        <img class="w-12 h-12 rounded-full mr-4" src="{{ $document->creator->profile_photo_url }}"
                            alt="{{ $document->creator->fullName }}">
                        <div>
                            <!-- User Name and Time -->
                            <div class="text-lg font-semibold text-gray-800">{{ $document->creator->fullName }}</div>
                            <div class="text-sm text-gray-500">{{ $document->created_at_human }}</div>
                            <div class="mt-4">
                                <!-- Document Description -->
                                <div class="text-gray-600 text-xs mt-2">{{ Str::words($document->description, 4, '...') }}</div>
                            </div>
                        </div>
                        <!-- Document Type Icon -->
                        <div class="ml-auto">
                            <a href="{{ $document->file_url }}" class="mr-16 mt-10" target="_blank">
                                <img class="w-8 h-8" src="{{ $document->file_photo_url }}">
                            </a>
                        </div>
                    </div>
                    <!-- Document Title -->
                    
                </div>
            @endforeach
        @else
            <p>No hay documentos disponibles.</p>
        @endif
        @if ($withShowMore)
            <a href="{{ route('documents') }}" class="mt-2 text-steal-600 p-2 inline-block">Ver todo <i
                    class="icon-arrow-right"></i></a>
        @endif
    </x-card>
</div>
