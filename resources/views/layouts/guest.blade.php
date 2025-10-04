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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="relative min-h-screen">
        <!-- Blurred green background -->
        <div aria-hidden="true" style="position:fixed; inset:0; background:var(--color-primary); filter: blur(12px); -webkit-filter: blur(12px); z-index:0;"></div>

        <div class="relative z-10 font-sans text-gray-900 dark:text-gray-100 antialiased">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </div>

        @livewireScripts
    </body>
</html>
