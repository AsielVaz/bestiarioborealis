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
    </head>
    <body class="arcane-shell font-sans text-stone-100 antialiased">
        <div class="arcane-main flex min-h-screen flex-col items-center px-4 pt-8 sm:justify-center sm:pt-0">
            <div class="text-center">
                <a href="/" class="inline-flex flex-col items-center gap-2">
                    <img src="{{ asset('bestiary-assets/seal_round_01.png') }}" alt="" class="h-20 w-20 object-contain opacity-90">
                    <span class="text-lg font-semibold text-amber-200">Bestiario Borealis</span>
                </a>
            </div>

            <div class="arcane-card mt-6 w-full overflow-hidden rounded-lg px-6 py-5 shadow-md sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
