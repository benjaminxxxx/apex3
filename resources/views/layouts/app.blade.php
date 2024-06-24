<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    <link href="{{asset('css/fontello.css')}}" rel="stylesheet" /> 
    <link href="{{asset('css/custom.css')}}" rel="stylesheet" /> 
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100" x-data="{ isOpenMenu: false }" x-bind:class="{ 'is-active-menu': isOpenMenu }">
        @livewire('navigation-menu')

        <aside id="logo-sidebar" x-bind:class="{ 'active-menu-width': isOpenMenu }"
            class="fixed top-0 left-0 z-40 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 menu-width"
            aria-label="Sidebar">
            
            <div class="h-full px-3 pb-4 overflow-y-auto dark:bg-gray-800 md:p-7" style="background:#FCFCFC">
                <ul class="space-y-2 font-medium" x-bind:class="{ 'active-menu': isOpenMenu, 'inactive-menu': !isOpenMenu }">
              
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('actividad')}}" icon="icon-telegram-2" menu="Actividad" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('usuarios')}}" icon="icon-user-3" menu="Miembros" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('grupos')}}" icon="icon-signal-2" menu="Grupos" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('eventos')}}" icon="icon-copy" menu="Eventos" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('documentos')}}" icon="icon-telegram-2" menu="Documentos" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('noticias')}}" icon="icon-feather-2" menu="Noticias" />
                   <x-nav-link href="{{route('usuarios')}}" active="{{request()->routeIs('contacto')}}" icon="icon-email-3" menu="Contacto" />
                   
                </ul>
            </div>
        </aside>

        <div class="p-4 main-container h-screen mt-20" style="box-shadow:inset 0 0 15px 5px #e4e4e4">
            <!-- Page Content -->
            <div>
                {{$header}}
            </div>
            <main>
                {{ $slot }}
            </main>
        </div>

    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
