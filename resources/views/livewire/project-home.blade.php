<div>
    @if(Auth::user()->hasPermission('add_projects'))
    <x-card>
        @if ($errors->has('error_message'))
            <x-message-error>
                {{ $errors->first('error_message') }}
            </x-message-error>
        @endif
        <form wire:submit="store">
            <x-h3>{{__('Create a new project')}}</x-h3>
            <div class="flex items-center mt-2">
                <x-input type="text" wire:model="name" placeholder="{{__('Project Name')}}" />
                <x-button type="submit" class="ml-4">
                    Crear
                </x-button>
            </div>
            <x-input-error for="name"/>
        </form>
    </x-card>
    @endif
    @php
        use Carbon\Carbon;
    @endphp
    @if($projects->count())
    <div class="groups grid grid-cols-3 gap-10 mt-2 lg:mt-5">
        @foreach ($projects as $project)
            <x-pop class="col-span-3 md:col-span-2 lg:col-span-1">
                <div class="relative">
                    <img class="w-full h-32 object-cover" src="{{ $project->cover_image_url }}" alt="Cover Image">
                    <img class="absolute inset-x-0 bottom-0 mx-auto mb-4 w-20 h-20 rounded-full border-4 border-white object-cover"
                        src="{{ $project->profile_image_url }}" alt="Profile Image">
                </div>
                <div class="text-center p-6">
                    <a class="text-xl font-semibold" href="{{ route('project',['slug'=>$project->project_code]) }}">{{ $project->name }}</a>
                    <p class="text-gray-600 text-sm my-3">{{ Carbon::parse($project->created_at)->diffForHumans() }}</p>
                    @if(Auth::user()->hasPermission('add_projects'))
                    <x-danger-button wire:click="destroy({{$project->id}})">
                        Eliminar grupo
                    </x-danger-button>
                    @endif
                </div>
            </x-pop>
        @endforeach
    </div>
    @else
    <x-card>
       {{__('No projects assigned yet')}}
    </x-card>
    @endif
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
