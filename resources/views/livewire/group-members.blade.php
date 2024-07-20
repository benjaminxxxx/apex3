<div>
    <x-card>
        <x-h3>{{ __('Search and add new Partner') }}</x-h3>
        <div class="flex items-start mt-2">
            <div class="relative">
                <x-input type="text" wire:model="user_search" wire:keyup="search" placeholder="Nombre del usuario" />
                <x-input-error for="user_search" />

                <x-label>{{ __('Search by name and email, otherwise register a new user') }}</x-label>
                @if ($users && $users->count() > 0)
                    <!-- Contenedor de resultados -->
                    <div class="absolute w-full bg-white mt-2 rounded-lg shadow-lg z-10">
                        <div class="space-y-4 p-4">
                            @foreach ($users as $user)
                                @php
                                    $isMember = $user->groupPartners->contains($group_id);
                                @endphp
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <img class="w-12 h-12 rounded-full object-cover"
                                        src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div>
                                        <div class="flex items-center">
                                            <span class="font-semibold text-lg">{{ $user->name }}</span>
                                            <span class="text-sm text-gray-500 ml-2">({{ $user->role->name }})</span>
                                        </div>
                                        <span class="text-gray-500">{{ $user->email }}</span>
                                    </div>
                                    <div>
                                        @unless ($isMember)
                                            <x-success-button type="button" wire:click="addMember({{ $user->id }})">
                                                <i class="icon icon-plus"></i>
                                            </x-success-button>
                                        @endunless
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <x-button wire:click="openForm" type="submit" class="ml-4">
                {{ __('Register partner') }}
            </x-button>
        </div>
    </x-card>

    <div class="gap-5 grid grid-cols-6">

        @foreach ($members as $member)
            @php
                $userRoleName = config("roles.{$member->user_role}");
            @endphp

            <x-card class="col-span-6 md:col-span-3  lg:col-span-2">

                <div class="flex flex-col items-center">
                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $member->profile_photo_url }}"
                        alt="Bonnie image" />
                    <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $member->name }}</h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $userRoleName }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $member->emai }}</span>
                    <div class="flex mt-4 md:mt-6">
                        <x-danger-button class="" wire:click="destroyFromGroup({{ $member->id }})">
                            Eliminar del grupo
                        </x-danger-button>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
    <!-- Modal -->
    <x-dialog-modal wire:model="isFormOpen">
        <x-slot name="title">
            Agregar Nuevo Socio
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <x-label for="nickname">DNI</x-label>
                    <x-input type="text" wire:model="nickname" id="nickname" />
                    <x-input-error for="nickname" />
                </div>
                <div class="mb-4">
                    <x-label for="name">Nombres</x-label>
                    <x-input type="text" wire:model="name" id="name" />
                    <x-input-error for="name" />
                </div>
                <div class="mb-4">
                    <x-label for="lastname">Apellidos</x-label>
                    <x-input type="text" wire:model="lastname" id="lastname" />
                    <x-input-error for="lastname" />
                </div>
                <div class="mb-4">
                    <x-label for="email">Email</x-label>
                    <x-input type="text" wire:model="email" id="email" />
                    <x-input-error for="email" />
                </div>
                <div class="mb-4">
                    <x-label for="password">Contraseña</x-label>
                    <x-input type="password" wire:model="password" id="password" />
                    <x-input-error for="password" />
                </div>
                <div class="mb-4">
                    <x-label for="role_id">Rol</x-label>
                    <x-select wire:model="role_id" id="role_id"
                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->UpperName }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="role_id" />
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button type="button" wire:click="closeForm" class="mr-2">{{__('Cancel')}}</x-secondary-button>
            <x-button type="button" wire:click="save" class="ml-3">{{__('Save')}}</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
