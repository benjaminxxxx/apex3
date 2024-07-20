<div>
    <x-card>
        <div class="mb-2 md:mb-4">
            <x-button wire:click="openForm()">Agregar Nuevo Gestor</x-button>
        </div>
        <x-table>
            <x-slot name="thead">
                <tr>
                    <x-th value="DNI del gestor" />
                    <x-th value="Nombres completos" />
                    <x-th value="Correo electrónico" />
                    <x-th value="Proyectos gestionados" />
                    <x-th value="Fecha Nacimiento" />
                    <x-th value="Número" />
                    <x-th value="Dirección" />
                    <x-th value="Acciones" />
                </tr>
            </x-slot>
            <x-slot name="tbody">
                @if ($managers)
                    @foreach ($managers as $manager)
                      
                        <x-tr>
                            <x-th value="{{ $manager->nickname }}" />
                            <x-th value="{{ $manager->fullName }}" />
                            <x-th value="{{ $manager->email }}" />
                            <x-th value="{{ $manager->projectsString }}" />
                            <x-th value="{{ $manager->birthdate }}" />
                            <x-th value="{{ $manager->phone }}" />
                            <x-th value="{{ $manager->address }}" />
                            <td>
                                <div class="flex items-center">
                                    <x-secondary-button wire:click="edit('{{ $manager->user_code }}')">
                                        <i class="icon-pencil"></i>
                                    </x-secondary-button>
                                    @if ($manager->status == '0')
                                        <x-warning-button wire:click="enable('{{ $manager->user_code }}')"
                                            class="ml-1">
                                            <i class="icon-block"></i>
                                        </x-warning-button>
                                    @else
                                        <x-success-button wire:click="disable('{{ $manager->user_code }}')"
                                            class="ml-1">
                                            <i class="icon-check"></i>
                                        </x-success-button>
                                    @endif
                                    @if ($manager->managedProjects->count() == 0)
                                        <x-danger-button wire:click="confirmDelete('{{ $manager->user_code }}')"
                                            class="ml-1">
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
    <x-dialog-modal wire:model="isFormOpen" maxWidth="full">
        <x-slot name="title">
            Agregar Nuevo Gestor
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="grid grid-cols-3 gap-5">
                <div class="col-span-3 lg:col-span-2">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="nickname">Documento</x-label>
                            <x-input type="text" wire:model="nickname" id="nickname" />
                            <x-input-error for="nickname" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="name">Nombres</x-label>
                            <x-input type="text" wire:model="name" id="name" />
                            <x-input-error for="name" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="lastname">Apellidos</x-label>
                            <x-input type="text" wire:model="lastname" id="lastname" />
                            <x-input-error for="lastname" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="email">Email</x-label>
                            <x-input type="text" wire:model="email" id="email" />
                            <x-input-error for="email" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="password">Contraseña</x-label>
                            <x-input type="password" wire:model="password" id="password" />
                            <x-input-error for="password" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="role_id">Rol</x-label>
                            <x-select wire:model="role_id" id="role_id">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->UpperName }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="role_id" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="birthdate">Fecha de cumpleaños</x-label>
                            <x-input type="text" wire:model="birthdate" id="birthdate" />
                            <x-input-error for="birthdate" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="phone">Telefono</x-label>
                            <x-input type="text" wire:model="phone" id="phone" />
                            <x-input-error for="phone" />
                        </div>
                        <div class="mb-4 col-span-2 lg:col-span-1">
                            <x-label for="address">Dirección</x-label>
                            <x-input type="text" wire:model="address" id="address" />
                            <x-input-error for="address" />
                        </div>
                    </div>

                </div>
                @if ($projects)
                    <div class="col-span-3 lg:col-span-1">
                        <x-h3>Proyectos a gestionar</x-h3>
                        <ul
                            class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach ($projects as $project)
                                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                    <div class="flex items-center ps-3">
                                        <input id="managedProjects{{ $project->id }}" type="checkbox"
                                            wire:model="managedProjects" value="{{ $project->id }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="managedProjects{{ $project->id }}"
                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ mb_strtoupper($project->name) }}</label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button type="button" wire:click="closeForm" class="mr-2">Cancelar</x-secondary-button>
            <x-button type="button" wire:click="save" class="ml-3">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>

    <x-confirmation-modal id="confirmDeleteModal" wire:model="isDeleting">
        <x-slot name="title">
            Confirmar Eliminación
        </x-slot>
        <x-slot name="content">
            ¿Estás seguro que deseas eliminar este usuario?
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="cancelDelete">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click="deleteUser" class="ml-2">
                Eliminar
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    @if (session()->has('message'))
        <x-toast class="bg-green-600">
            {{ session('message') }}
        </x-toast>
    @endif
    @if (session()->has('error'))
        <x-toast class="bg-red-600">
            {{ session('error') }}
        </x-toast>
    @endif
</div>
