<div>
    <x-card>
        <div class="mb-2 md:mb-4">
            <x-button wire:click="openForm()">Agregar Nuevo Miembro</x-button>
        </div>
        <x-table>
            <x-slot name="thead">
                <tr>
                    <x-th value="Nombre de usuario" />
                    <x-th value="Nombres" />
                    <x-th value="Correo electrónico" />
                    <x-th value="Perfil" />
                    <x-th value="Asignación" />
                    <x-th value="Editar" />
                </tr>
            </x-slot>
            <x-slot name="tbody">
                @if ($users)
                    @foreach ($users as $user)
                        <x-tr>
                            <x-th value="{{ $user->nickname }}" />
                            <x-th value="{{ $user->name . ', ' . $user->lastname }}" />
                            <x-th value="{{ $user->email }}" />
                            <x-th value="{{ $user->role_id }}" />
                            <x-th value="0" />
                            <td>
                                <div class="flex gap-3">
                                    <x-secondary-button wire:click="edit({{ $user->id }})">
                                        <i class="icon-pencil"></i>
                                    </x-secondary-button>
                                    @if (Auth::id() != $user->id)
                                        @if ($user->status == '0')
                                            <x-warning-button @click="$dispatch('askEnableUser', { userId: {{ $user->id }} })"
                                                class="ml-1">
                                                <i class="icon-block"></i>
                                            </x-warning-button>
                                        @else
                                            <x-success-button @click="$dispatch('askDisableUser', { userId: {{ $user->id }} })"
                                                class="ml-1">
                                                <i class="icon-check"></i>
                                            </x-success-button>
                                        @endif
                                        <x-danger-button  @click="$dispatch('askDeleteUser', { userId: {{ $user->id }} })" class="ml-1">
                                            <i class="icon-trash"></i>
                                        </x-danger-button>
                                    @endif
                                </div>
                            </td>
                        </x-tr>
                    @endforeach
                @endif
            </x-slot>
        </x-table>
    </x-card>
  
    <!-- Modal -->
    <x-dialog-modal wire:model="isFormOpen">
        <x-slot name="title">
            Agregar Nuevo Miembro
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <x-label for="nickname">Nickname</x-label>
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
                            <option value="{{ $role->id }}">{{ mb_strtoupper($role->name) }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="role_id" />
                </div>
                <div class="mb-4">
                    <x-label for="role_id">Proyecto</x-label>
                    <x-select wire:model="role_id" id="role_id"
                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ mb_strtoupper($project->name) }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="role_id" />
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button type="button" wire:click="closeForm" class="mr-2">Cancelar</x-secondary-button>
            <x-button type="button" wire:click="save" class="ml-3">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
    @if (session()->has('message'))
        <div id="toast-top-right"
            class="fixed flex mt-24 z-50 items-center w-full max-w-xs p-4 space-x-4 text-white bg-green-600 divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
            role="alert">
            <div class="text-sm font-normal flex w-full">

                {{ session('message') }}

                <button type="button" onclick="this.parentElement.parentElement.style.display='none';"
                    class="ms-auto -mx-1.5 -my-1.5 text-white hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-undo" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
    
</div>
