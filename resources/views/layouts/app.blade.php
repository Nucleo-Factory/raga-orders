<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
        @livewireStyles
    </head>
    <body>
        <!-- Aqui debe ir el componente sidebar -->
        <livewire:partials.main-sidebar />
        <!-- Aqui debe ir el componente sidebar -->

        <!-- Static sidebar for desktop -->

        <div class="lg:pl-72 transition-all duration-500 h-full" :class="{ 'w-6rem': isCollapsed }">
            <!-- Aqui debe ir el componente header -->
            <livewire:layout.navigation />
            <!-- Aqui debe ir el componente header -->

            <main class="pt-10 pb-[150px] flex justify-between w-full relative ">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>

    @stack('scripts')
</html>
