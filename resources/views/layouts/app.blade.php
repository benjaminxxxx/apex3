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
    <link href="{{ asset('css/fontello.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/swal.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100" x-data="{ isOpenMenu: false }" x-bind:class="{ 'is-active-menu': isOpenMenu }">
        @livewire('navigation-menu')

        <aside id="logo-sidebar" x-bind:class="{ 'active-menu-width': isOpenMenu }"
            class="fixed top-0 left-0 z-40 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 menu-width"
            aria-label="Sidebar">

            <div class="h-full px-3 pb-4 overflow-y-auto dark:bg-gray-800 md:p-7" style="background:#FCFCFC">
                <ul class="space-y-2 font-medium"
                    x-bind:class="{ 'active-menu': isOpenMenu, 'inactive-menu': !isOpenMenu }">

                    <x-nav-link href="{{ route('usuarios') }}" active="{{ request()->routeIs('actividad') }}"
                        icon="icon-telegram-2" menu="Actividad" />
                    <x-nav-link href="{{ route('usuarios') }}" active="{{ request()->routeIs('usuarios') }}"
                        icon="icon-user-3" menu="Miembros" />
                    <x-nav-link href="{{ route('usuarios') }}" active="{{ request()->routeIs('grupos') }}"
                        icon="icon-signal-2" menu="Grupos" />
                    <x-nav-link href="{{ route('usuarios') }}" active="{{ request()->routeIs('eventos') }}"
                        icon="icon-copy" menu="Eventos" />
                    <x-nav-link href="{{ route('charts') }}" active="{{ request()->routeIs('charts') }}"
                        icon="icon-analytics" menu="Gráficos" />
                    <x-nav-link href="{{ route('usuarios') }}" active="{{ request()->routeIs('documentos') }}"
                        icon="icon-telegram-2" menu="Documentos" />
                    <x-nav-link href="{{ route('notices') }}" active="{{ request()->routeIs('notices') }}"
                        icon="icon-feather-2" menu="Noticias" />
                    <x-nav-link href="{{ route('contact') }}" active="{{ request()->routeIs('contact') }}"
                        icon="icon-email-3" menu="Contacto" />

                </ul>
            </div>
        </aside>

        <div class="p-4 main-container min-h-screen mt-20" style="box-shadow:inset 0 0 15px 5px #e4e4e4">
            <!-- Page Content -->
            <div>
                {{ $header }}
            </div>
            <main>
                {{ $slot }}
            </main>
        </div>

    </div>

    @stack('modals')
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"
        integrity="sha384-2huaZvOR9iDzHqslqwpR87isEmrfxqyWOF7hr7BY6KG0+hVKLoEXMPUJw3ynWuhO" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Importamos Tippy.js para los tooltips -->
    <script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js" integrity="sha512-JRlcvSZAXT8+5SQQAvklXGJuxXTouyq8oIMaYERZQasB8SBDHZaUbeASsJWpk0UUrf89DP3/aefPPrlMR1h1yQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src='https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js'></script>
    <script src="https://cdn.tiny.cloud/1/0t7v1pq1uxcl2ehyauztppdjsypqly9r55zipgmeqwbvu77q/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
   
    @livewireScripts
    @stack('scripts')
</body>

</html>
