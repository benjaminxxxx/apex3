<x-app-layout>
    <x-slot name="title">
        Error de acceso
    </x-slot>

    <x-slot name="header">
    </x-slot>
    <x-card class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Danger</span>
        <div>
            <span class="font-medium">{{ __('The page was not found.') }}</span>
            <ul class="mt-1.5 list-disc list-inside">
                <li>{{ __('Verify that the shared link comes from a reliable source.') }}</li>
                <li>{{ __('Make sure you have the necessary permissions to view the page.') }}</li>
                <li>{{ __('It is possible that the page has been deleted or its visibility permissions have changed.') }}
                </li>
            </ul>
        </div>
    </x-card>
</x-app-layout>
