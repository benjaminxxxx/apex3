<div>
    @if (session()->has('message'))
        <x-message-success>
            {{ session('message') }}
        </x-message-success>
    @endif

    <x-loading wire:loading wire:target="submitForm" />

    <form wire:submit.prevent="submitForm">

        <x-label>Esta información de contacto será visible para todos los usuarios, y se pondrán en contacto contigo en caso necesiten algún tipo de ayuda</x-label>
        <x-input wire:model="name" placeholder="Tu nombre de contacto"  class="mt-6"/>
        <x-input-error for="name"/>
        
        <x-input wire:model="email" placeholder="Tu email de contacto"  class="mt-6"/>
        <x-input-error for="email"/>

        <x-input wire:model="number" placeholder="Tu número de contacto"  class="mt-6"/>
        <x-input-error for="number"/>

        <x-input wire:model="number1" placeholder="Tu número de contacto alternativo"  class="mt-6"/>
        <x-input-error for="number1"/>
        
        <x-input wire:model="address" rows="5" placeholder="Tu dirección de contacto" class="mt-6"/>
        <x-input-error for="address"/>

        <div class="text-right">
            <x-button type="submit" class="mt-6">
                GUARDAR
            </x-button>
        </div>

    </form>
</div>
