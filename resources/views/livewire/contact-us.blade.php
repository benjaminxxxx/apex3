<div>
    @if (session()->has('message'))
        <x-message-success>
            {{ session('message') }}
        </x-message-success>
    @endif

    <x-loading wire:loading wire:target="submitForm" />
    
    <form wire:submit.prevent="submitForm" >
        
        <x-input wire:model="name" placeholder="Tu nombre"  class="mt-6"/>
        <x-input-error for="name"/>
        
        <x-input wire:model="email" placeholder="Tu email"  class="mt-6"/>
        <x-input-error for="email"/>
        
        <x-textarea wire:model="message" rows="5" placeholder="Tu mensaje" class="mt-6"></x-textarea>
        <x-input-error for="message"/>

        <x-button type="submit" class="mt-6">
            ENVIAR
        </x-button>

        <div class="inline-flex items-start mb-5 lg:ml-5">
            <div class="flex items-center h-5">
                <input id="terms" wire:model="terms" type="checkbox"
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-cyan-600" />
            </div>
            <label for="terms" class="ms-2 text-sm font-medium text-gray-500 dark:text-gray-300">Acepto que los datos
                que he enviado se recopilen y almacenen.</label>
                <x-input-error for="terms"/>
        </div>
    </form>
</div>
