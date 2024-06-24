<x-guest-layout>
    
    <x-slot name="title">
        Ingresar
    </x-slot>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1 class="text-xl font-bold leading-tight text-center tracking-tight md:text-4xl dark:mb-2 md:mb-5">
                Bienvenido!
            </h1>
            <div>
                <x-label for="email" value="Email"  />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Contraseña" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm">Recuerdame</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm hover:text-cyan-800 text-cyan-700 rounded-md focus:outline-none  focus:ring-cyan-700"
                        href="{{ route('password.request') }}">
                        ¿Has olvidado la contraseña?
                    </a>
                @endif

                <x-button class="ms-4">
                    Ingresar
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
