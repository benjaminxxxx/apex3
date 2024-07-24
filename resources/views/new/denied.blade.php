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
            <span class="font-medium">La noticia solicitada no se encontró.</span>
            <ul class="mt-1.5 list-disc list-inside">
                <li>Verifique que el enlace compartido proviene de una fuente confiable.</li>
                <li>Asegúrese de que tiene los permisos necesarios para ver la noticia.</li>
                <li>Es posible que la noticia haya sido eliminada o que sus permisos de visibilidad hayan cambiado.
                </li>
            </ul>
        </div>
    </x-card>
</x-app-layout>
