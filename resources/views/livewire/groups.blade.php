<div>
    @if(Auth::user()->hasPermission('add_group'))
    <x-card>
        @if ($errors->has('error_message'))
            <x-message-error>
                {{ $errors->first('error_message') }}
            </x-message-error>
        @endif
        <form wire:submit="store">
            <x-h3>{{__('Create a new group')}}</x-h3>
            <div class="flex items-center mt-2">
                <x-input type="text" wire:model="name" placeholder="{{__('Group Name')}}" />
                <x-button type="submit" class="ml-4">
                    Crear Grupo
                </x-button>
            </div>
            <x-input-error for="name"/>
        </form>
    </x-card>
    @endif
    @if ($groups->count())
        <div class="groups grid grid-cols-3 gap-10 mt-2 lg:mt-5">
            @foreach ($groups as $group)
                <x-pop class="col-span-3 md:col-span-2 lg:col-span-1">
                    <div class="text-center p-6">
                        <div class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <a href="{{route('group.go',['slug'=>$group->slug])}}"
                                class="text-lg font-semibold text-gray-900 dark:text-white">{{ $group->name }}</a>
                            <p class="my-4">{{ $group->partners->count() }} Socios</p>
                        </div>
                        <div class="flex items-center justify-center ">
                            <img class="rounded-full w-9 h-9" src="{{ $group->manager->profile_photo_url }}"
                                alt="profile picture">
                            <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                                <div>{{ $group->manager->fullName }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 ">{{ $group->manager->role->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </x-pop>
            @endforeach
        </div>
    @else
        <x-card>
            Aún no hay grupos para este proyecto
        </x-card>
    @endif
</div>
