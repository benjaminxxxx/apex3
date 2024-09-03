<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-white md:text-gray-600 px-10 text-center">
            {{__('messages.forgot_password')}}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4 mx-10" />

        <form method="POST" action="{{ route('password.email') }}" class="w-full px-10 max-w-[24rem]">
            @csrf

            <div class="block">
                <x-label for="email" class="text-white md:text-gray-600" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="w-full">
                    {{ __('messages.email_password_reset_link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
