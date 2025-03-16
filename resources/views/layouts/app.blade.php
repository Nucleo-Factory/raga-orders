<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:700,800|figtree:400,500,600|inter:400,500,600" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
        @livewireStyles
    </head>

    <body class="bg-[#EDEDED] sm:flex">
        <!-- Aqui debe ir el componente sidebar -->
        <livewire:partials.main-sidebar />
        <!-- Aqui debe ir el componente sidebar -->

        <!-- Static sidebar for desktop -->

        <div class="h-full transition-all duration-500 pl-14 lg:pl-20 grow" :class="{ 'w-6rem': isCollapsed }">
            <!-- Aqui debe ir el componente header -->
            <livewire:partials.main-header />
            <!-- Aqui debe ir el componente header -->

            <main class="relative flex w-full justify-between px-4 sm:px-0">
                <div class="w-full space-y-5 px-10">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>

    @stack("scripts")

</html>
