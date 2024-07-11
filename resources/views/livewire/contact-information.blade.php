<div>
    <x-pop>
        <div class="p-6">
            <x-h3>{{ __('Contact information') }}</x-h3>
            @if ($contact)
                <ul class="space-y-4 mt-5">

                    <li class="flex items-center">
                        <x-i class="icon-user"></x-i>
                        <x-label>Nombre: {{ $contact->name }}</x-label>
                    </li>
                    <li class="flex items-center">
                        <x-i class="icon-location"></x-i>
                        <x-label>Dirección: {{ $contact->address }}</x-label>
                    </li>
                    <li class="flex items-center">
                        <x-i class="icon-phone-2"></x-i>
                        <x-label>Celular: {{ $contact->number }}</x-label>
                    </li>
                    <li class="flex items-center">
                        <x-i class="icon-smartphone"></x-i>
                        <x-label>Número Adicional: {{ $contact->number1 }}</x-label>
                    </li>
                    <li class="flex items-center">
                        <x-i class="icon-email"></x-i>
                        <x-label>Email: {{ $contact->email }}</x-label>
                    </li>
                </ul>
            @else
                <x-label>Aún no tenemos información de contacto</x-label>
            @endif
        </div>
    </x-pop>
</div>
