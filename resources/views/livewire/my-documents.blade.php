<div>


    @if ($documents)
        @foreach ($documents as $document)
            <x-card class="max-w-3xl mx-auto mt-2 relative">
              
                <!-- Document Post -->
                <div class="flex items-start">
                    <!-- User Avatar -->
                    <img class="w-12 h-12 rounded-full mr-4" src="{{ $document->creator->profile_photo_url }}"
                        alt="{{ $document->creator->fullName }}">
                    <div>
                        <!-- User Name and Time -->
                        <div class="text-lg font-semibold text-gray-800">{{ $document->creator->fullName }}</div>
                        <div class="text-sm text-gray-500">{{ $document->created_at_human }}</div>
                    </div>
                    <!-- Document Type Icon -->
                    <div class="ml-auto">
                        <a href="{{ $document->file_url }}" class="mr-16 mt-10" target="_blank">
                            <img class="w-8 h-8" src="{{ $document->file_photo_url }}">
                        </a>
                    </div>
                </div>
                <!-- Document Title -->
                <div class="mt-4">
                    <!-- Document Description -->
                    <div class="text-gray-600 mt-2">{{ $document->description }}</div>
                </div>
            
                <!-- Additional Document Posts -->
                <!-- Repeat the above block for each document post -->
            </x-card>
        @endforeach
        @if ($documents->count() == 0)
            <x-card>
                <x-label class="mt-10 text-center">Aún no te han enviado documentos.</x-label>
            </x-card>
        @endif

    @endif

</div>
